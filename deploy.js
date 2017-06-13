'use strict';

module.exports.config = {
    default: {
        commands: {
            local: [
                "curl -sS https://getcomposer.org/installer | php",
                "php composer.phar install --optimize-autoloader --no-progress --no-dev --no-scripts",
            ]
        },
        requirements: {
            local: [
                "php",
            ],
            remote: [
                "php"
            ]
        },
        ignores: [
            ".git",
            ".gitignore",
            "deploy.js",
            "web/app_dev.php",
            "web/config.php",
        ],
        shared: {
            files: [
                "app/config/parameters.yml"
            ],
            folders: [
                "var/logs",
                "var/sessions"
            ]
        },
        releases: 3
    },
    production: {
        commands: {
            remote: [
                // "sed -r -i \"s#version:[[:space:]a-zA-Z0-9~]+\\$#version: $(date +\"%Y%d%m%H%M%S\")#g\" $(readlink -e app/config/parameters.yml)",
                "export SYMFONY_ENV=prod",
                "SYMFONY_ENV=prod php composer.phar run-script post-update-cmd",
                "SYMFONY_ENV=prod php bin/console doctrine:schema:update --env=prod --force",
                "chown -R sb:www-data bin/console var/{cache,logs} web/app.php",
                "chmod 775 -R bin/console var/{cache,logs} web/app.php"
            ]
        },
        servers: [{
            user: "sb",
            host: "vps399684.ovh.net",
            to: "/home/sb/slam-booking.net"
        }]
    }
};
