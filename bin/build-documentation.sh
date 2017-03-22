#!/bin/bash
# @Date:   2016-06-24T09:45:16-03:00
# @Modified at 2016-06-24T09:45:37-03:00

vendor/bin/phpunit --testdox | grep -vi php |  sed "s/.*\[/-&/" | \
sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/.*Gpupo.*/&\'$'\n/g' |\
sed 's/Gpupo\\Tests\\/### /g' > Resources/doc/testdox.md;

cat Resources/doc/libraries-list.md | sed 's/  * / | /g' | sed 's/e0 / | /g' > Resources/doc/libraries-table.md;

echo '' > README.md;
names='main require license QA thanks install console links links-common dev todo dev-common testdox libraries-table footer-common'
for name in $names
do
  touch Resources/doc/${name}.md;
  printf '<!-- '  >>  README.md;
  printf "$name"  >>  README.md;
  printf ' -->'  >>  README.md;
  printf "\n\n"  >>  README.md;
  cat Resources/doc/${name}.md >> README.md;
  printf "\n"  >>  README.md;
done
