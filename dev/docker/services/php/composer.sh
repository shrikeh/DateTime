#!/bin/sh
function setup_composer() {
    declare EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')";
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');";
    declare ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")";

    if [ "${EXPECTED_CHECKSUM}" != "${ACTUAL_CHECKSUM}" ]
    then
        >&2 echo 'ERROR: Invalid installer checksum';
        rm composer-setup.php;
        exit 1;
    fi

    php composer-setup.php --quiet;
    declare RESULT=$?;
    rm composer-setup.php;
    exit ${RESULT}
}

setup_composer;
