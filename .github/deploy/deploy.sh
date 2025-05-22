#!/usr/bin/env bash

usage()
{
cat << EOF
usage: $0 options

This script deploys git projects on remote servers

The following environment variables are required:
 - DEPLOYMENT_SSH_SERVER
 - DEPLOYMENT_SSH_USER
 - DEPLOYMENT_REPOPATH
 - DEPLOYMENT_WORKTREE
EOF
}

CREATE_WORKTREE=true

if [[ -z "$DEPLOYMENT_SSH_SERVER" ]] || [[ -z "$DEPLOYMENT_SSH_USER" ]] || [[ -z "$DEPLOYMENT_REPOPATH" ]] || [[ -z "$DEPLOYMENT_WORKTREE" ]]
then
     usage
     exit 1
fi

#ssh-keyscan -H "$DEPLOYMENT_SSH_SERVER" >> ~/.ssh/known_hosts

DEPLOYMENT_TARGET="$DEPLOYMENT_SSH_USER@$DEPLOYMENT_SSH_SERVER"

# Defaults
if [[ -z "$COMPOSER_COMMAND" ]]; then
    COMPOSER_COMMAND="composer"
fi
if [[ -z "$CREATE_WORKTREE" ]]; then
    CREATE_WORKTREE="true"
fi

if [[ -n "$COMPOSER_HOME" ]]; then
    COMPOSER_HOME="COMPOSER_HOME=${COMPOSER_HOME} "
fi

BRANCH=${GITHUB_REF#refs/heads/}

SSH_OPTIONS='-o BatchMode=yes -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no'

# Existenz des work trees checken
TREE_EXISTS=$(ssh $SSH_OPTIONS $DEPLOYMENT_TARGET " test -d $DEPLOYMENT_WORKTREE; echo \$?")
if [ "1" == "$TREE_EXISTS" ]; then
    echo "Work Tree $DEPLOYMENT_WORKTREE does not exist"
    if [ "true" == "$CREATE_WORKTREE" ]; then
        echo "Creating Work Tree $DEPLOYMENT_WORKTREE"
        TREE_CREATED=$(ssh $SSH_OPTIONS $DEPLOYMENT_TARGET " mkdir -p $DEPLOYMENT_WORKTREE; echo \$?");
        if [ "0" != "$TREE_CREATED" ]; then
            echo "Creating Work Tree $DEPLOYMENT_WORKTREE failed!"
            exit 1;
        fi
    else
        exit 1;
    fi
else
    echo "Work Tree $DEPLOYMENT_WORKTREE exists"
fi


# Status des Repositories auf dem Remote Server checken
if [ "$DEPLOYMENT_CLEAN" != "false" ]; then
    echo "ssh -o \"BatchMode yes\" $DEPLOYMENT_TARGET \" git --git-dir=$DEPLOYMENT_REPOPATH --work-tree=$DEPLOYMENT_WORKTREE status\""
    STATUS=$(ssh $SSH_OPTIONS $DEPLOYMENT_TARGET " git --git-dir=$DEPLOYMENT_REPOPATH --work-tree=$DEPLOYMENT_WORKTREE status --untracked-files=no")

    if echo $STATUS | grep -qi "working directory clean" || echo $STATUS | grep -qi "nothing to commit" || echo $STATUS | grep -qi "Arbeitsverzeichnis unver" || echo $STATUS | grep -qi "nichts zu committen"; then
      echo "${REMOTE_HOST}:${REMOTE_TREE} is clean as required"
    elif echo $STATUS | grep -qi "Not a git repository"; then
        echo "$DEPLOYMENT_REPOPATH is not a Git repository"
        exit 1;
    else
        echo "${REMOTE_HOST}:${REMOTE_TREE} is unclean. Fix this before you push to $BRANCH"
        echo $STATUS;
        exit 1;
    fi
fi

# Checkout
echo ssh $SSH_OPTIONS $DEPLOYMENT_TARGET " git --git-dir=$DEPLOYMENT_REPOPATH --work-tree=$DEPLOYMENT_WORKTREE checkout -f origin/$BRANCH"
#ssh $SSH_OPTIONS $DEPLOYMENT_TARGET " git --git-dir=$DEPLOYMENT_REPOPATH --work-tree=$DEPLOYMENT_WORKTREE checkout -f origin/$BRANCH"
echo Checkout branch $BRANCH ...
ssh $SSH_OPTIONS $DEPLOYMENT_TARGET cd $DEPLOYMENT_WORKTREE && git fetch origin $BRANCH && git checkout -f $BRANCH && git pull origin $BRANCH && git reset --hard origin/$BRANCH
STATUS="$?"

# Install composer packages
if [ -f "$GITHUB_WORKSPACE/composer.json" ] || [ -f "$GITHUB_WORKSPACE/composer.lock" ]; then
    if [ -z "$SKIP_COMPOSER" ]; then
        echo "Installing composer dependencies"
        COMPOSER_RESULT=$(ssh $SSH_OPTIONS $DEPLOYMENT_TARGET " ${COMPOSER_HOME}${COMPOSER_COMMAND} install -d \"$DEPLOYMENT_WORKTREE\" --no-dev --no-progress --ignore-platform-reqs --no-interaction --prefer-dist --optimize-autoloader");
        STATUS_CODE="$?"
        echo "$COMPOSER_RESULT"

        if [ "$STATUS_CODE" != "0" ]; then
            echo "Installation of composer dependencies failed"
            exit 1;
        fi
    else
        echo "Skipping composer step, the SKIP_COMPOSER flag is set"
    fi
fi

#  Rebuild Classes
ssh $SSH_OPTIONS $DEPLOYMENT_TARGET "grep -s pimcore:deployment:classes-rebuild $POST_DEPLOY_SCRIPT_PATH || $DEPLOYMENT_WORKTREE/bin/console -v pimcore:deployment:classes-rebuild -c"

exit $STATUS