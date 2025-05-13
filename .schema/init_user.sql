-- Adds a user to the database with all privileges. Use for local development only! Set username and password to your liking.
-- DO NOT USE IN PRODUCTION!
-- See config folder and set the user/password in the config.json file.
CREATE USER IF NOT EXISTS 'jtl-connector'@'%' IDENTIFIED BY 'jtl-connector';
GRANT ALL PRIVILEGES ON *.* TO 'jtl-connector'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;