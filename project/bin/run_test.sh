#!/bin/bash

. $(echo $0 | sed 's/[^\/]*$//')config.inc

if [ "$(echo $COUCHTEST | grep -E _test$)" == "" ]
then
    echo "La base COUCHTEST ($COUCHTEST) ne semble pas être une base de test ( doit se terminer par \"_test\", par exemple http://localhost:5984/giilda_app_test )"
    exit;
fi

while getopts ":x:t:" flag
do
    case "${flag}" in
        x) XMLFILE=${OPTARG};;
        t) TYPE_TEST=${OPTARG};;
    esac
done

shift $((OPTIND-1))

if ! test "$WORKINGDIR"; then
    WORKINGDIR=$(dirname $0)"/../"
fi

APPLICATIONS=$1

if [ ! $APPLICATIONS ]
then
    echo "Vous devez définir une application en argument :"
    echo ;
    echo "$0 [-t <unit|functional>] [-x <fichier xml de sortie>] [<application>] [<nom du test>]) ";
    exit;
fi

if test $APPLICATIONS = "all" ; then
    APPLICATIONS=$(find data/configuration/ -maxdepth 1 -type d | grep -v 'configuration/$' | sed 's/data.configuration.//' | tr '\n' ' ')
    NOM_TEST=""
    APPLICATIONOUTPUT=1
fi

for APPLICATION in $APPLICATIONS ; do

if test $APPLICATION == 'ava' ; then
    TEST_DIR="test_ava"
else
    TEST_DIR="test"
fi

if test "$APPLICATIONOUTPUT"; then
    echo $APPLICATION;
    APPLICATIONOUTPUT=" | sed 's/^/$APPLICATION : /' | grep -v '\.\.ok'"
fi

echo "Running test on $COUCHTEST"

NOM_TEST=$(echo $2 | sed 's/.*\///' | sed 's/Test\.*[a-z]*$//')
if ! test "$TYPE_TEST" && test "$NOM_TEST"; then
    TYPE_TEST=$( find $TEST_DIR -name "$(basename $NOM_TEST)"* | head -n 1 | awk -F '/' '{print $2}' )
fi

if test "$NOM_TEST"  && test "$TYPE_TEST" == "unit" ;
then
    APPLICATION=$APPLICATION COUCHURL=$COUCHTEST php symfony test:unit $NOM_TEST --trace
    exit;
fi

if test "$NOM_TEST"  && test "$TYPE_TEST" == "functional" ;
then
    APPLICATION=$APPLICATION COUCHURL=$COUCHTEST php symfony test:functional $APPLICATION $NOM_TEST --trace
    exit;
fi

if [ $NOM_TEST ]
then
    exit;
fi

curl -s -X DELETE $COUCHTEST > /dev/null
curl -s -X PUT $COUCHTEST > /dev/null || ( echo "connexion à $COUCHTEST impossible"  ;  exit 2 )

cd ..
make clean > /dev/null
make couchurl=$COUCHTEST > /dev/null
cd - > /dev/null

ls $WORKINGDIR"/data/configuration/"$APPLICATION | while read jsonFile
do
    curl -s -X POST -d @data/configuration/$APPLICATION/$jsonFile -H "content-type: application/json" $COUCHTEST > /dev/null
done

rm -rf cache/* > /dev/null
php symfony cc > /dev/null

bash -c "APPLICATION=$APPLICATION COUCHURL=$COUCHTEST NODELETE=1 php symfony test:all --xml=$XMLFILE $APPLICATIONOUTPUT"

done
