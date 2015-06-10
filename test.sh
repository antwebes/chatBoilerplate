cat<<EOF
Boilerplate test

EOF
echo "Clear test cache"

php app/console cache:clear -e=test

echo "find ladybug_dump";

cd ./src/
grep -rns "ldd(";
grep -rns " ld(";
grep -rns ";ld(";
grep -rns "{ld(";

cd ../vendor/antwebes/

grep -rns "ldd(";
grep -rns " ld(";
grep -rns ";ld(";
grep -rns "{ld(";

cd ../../

echo "PHPuNIT tests"
phpunit -c app/ src/AppBundle/Tests/Controller/ChateaVendorTest.php

echo "AppBundle Behat Test"
./bin/behat @AppBundle  --format progress