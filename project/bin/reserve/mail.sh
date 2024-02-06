#! /bin/bash

cat dr* | while read -r; do cut -d';' -f2; done | grep -v "[A-Z]" | sort | uniq > /tmp/listofdroperateur.txt
cat sv* | while read -r; do cut -d';' -f2; done | grep -v "[A-Z]" | sort | uniq > /tmp/listofsvoperateur.txt

mkdir /tmp/reservemail/ 2> /dev/null

cat /tmp/listofdroperateur.txt /tmp/listofsvoperateur.txt | while read -r cvi; do
	NOPG=false
	NOGW=false

	echo "$cvi"

	if ! grep -r "$cvi" | grep -q "pinot"; then
		NOPG=true
	fi

	if ! grep -r "$cvi" | grep -q "gewur"; then
		NOGW=true
	fi

	VOLPG=0
	VOLGW=0

	if [ "$NOPG" = false ]; then
		VOLPG=$(grep -r "$cvi" | grep "pinot" | cut -d';' -f9)
	fi

	if [ "$NOGW" = false ]; then
		VOLGW=$(grep -r "$cvi" | grep "gewur" | cut -d';' -f9)
	fi

	sed -e "s/%pg_vol%/$VOLPG/" -e "s/%gw_vol%/$VOLGW/" mail.txt > "/tmp/reservemail/$cvi"

	if [ "$NOPG" = true ]; then
		sed -i "/• Pinot Gris/d" "/tmp/reservemail/$cvi"
	fi

	if [ "$NOGW" = true ]; then
		sed -i "/• Gewurztraminer/d" "/tmp/reservemail/$cvi"
	fi

	echo "PG: $VOLPG NOPG: $NOPG GW: $VOLGW NOGW: $NOGW"
	echo
done


