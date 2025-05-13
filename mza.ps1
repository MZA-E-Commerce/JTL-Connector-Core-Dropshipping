# @copyright Copyright (C) 2025 MZA GmbH (https://www.mza.de)
# @author Christoph Klöppner (christoph.kloeppner@mza.de)

param (
    [string]$C
)

$env:DOCKER_BUILDKIT = "1"
$env:COMPOSE_DOCKER_CLI_BUILD = "1"

switch ($C) {
    "help" {
        Write-Host "`nUsage:`n  .\mza.ps1 -C <target>`n"
        Write-Host "Targets:"
        Write-Host "help    -> Show this help"
        Write-Host "build   -> Build The Image"
        Write-Host "up      -> Start service"
        Write-Host "down    -> Stop service and clean up"
        Write-Host "logs    -> Tail container logs"
        Write-Host "restart -> Restart container"
        Write-Host "rm      -> Remove current container"
        Write-Host "images  -> Show created images"
        Write-Host "ps      -> Show running containers"
        Write-Host "stats   -> Show service statistics"
        Write-Host "top     -> Show service processes"
        Write-Host "add-mysql-user -> Add MySQL user: jtl-connector"
    }
    "build" {
        docker compose build --pull --no-cache
    }
    "up" {
        Write-Host "Starting Application..."
        docker compose up -d --force-recreate --remove-orphans
    }
    "down" {
        docker compose down --remove-orphans
    }
    "logs" {
        docker compose logs --follow --tail=100
    }
    "restart" {
        docker compose restart
    }
    "rm" {
        docker compose rm -f
    }
    "images" {
        docker compose images
    }
    "ps" {
        docker compose ps
    }
    "stats" {
        docker compose stats
    }
    "top" {
        docker compose top
    }
    "add-mysql-user" {
        $containerRunning = docker compose ps --services --filter "status=running" | Select-String "jtl-connector-mysql"

        if (-not $containerRunning) {
            Write-Host "MySQL-Container ist nicht gestartet. Wird gestartet ..."
            docker compose up -d jtl-connector-mysql
            Start-Sleep -Seconds 5
        }
        docker compose exec db mysql -u root -pROOT -e "CREATE USER IF NOT EXISTS 'jtl-connector'@'%' IDENTIFIED BY 'jtl-connector'; GRANT ALL PRIVILEGES ON *.* TO 'jtl-connector'@'%'; FLUSH PRIVILEGES;"
        Write-Host "MySQL user successfully added..."

    }
    "phpstan" {
        Write-Host "Running PHPStan..."
        docker compose exec php bash -c "vendor/bin/phpstan analyse --configuration=phpstan.neon"
        Write-Host "PHPStan check completed."
    }
    "dshell" {
        docker exec -it jtl-connector-php bash
    }
    "dshellroot" {
        docker exec -it --user=root jtl-connector-php bash
    }
    default {
        Write-Host "Unknown command: $C"
        Write-Host "Use '.\mza.ps1 -C help' to see available commands."
    }
}