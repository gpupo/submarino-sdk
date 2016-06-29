#!/bin/bash
# @Date:   2016-06-24T09:45:16-03:00
# @Modified at 2016-06-24T09:45:37-03:00

vendor/bin/phpunit --testdox | grep -vi php |  sed "s/.*\[/-&/" | sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/Gpupo\\Tests\\/### /g' > var/logs/testdox.txt

cat Resources/doc/main.md Resources/doc/require.md Resources/doc/license.md \
Resources/doc/QA.md Resources/doc/thanks.md Resources/doc/install.md Resources/doc/console.md \
Resources/doc/links.md Resources/doc/dev.md Resources/doc/todo.md var/logs/testdox.txt > README.md;

cat Resources/doc/libraries-list.md | sed 's/  * / | /g' | sed 's/e0 / | /g' >> README.md;
