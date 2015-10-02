all: project/web/components/vins/vins-preview.html 

project/web/components/vins/vins-preview.html: project/web/components/vins/fontcustom.yml project/web/components/vins/svg/bouteille.svg  project/web/components/vins/svg/mouts.svg  project/web/components/vins/svg/raisins.svg  project/web/components/vins/svg/vrac.svg project/web/components/vins/svg/icon_help.svg
	cd project/web/components/vins ; fontcustom compile -c fontcustom.yml