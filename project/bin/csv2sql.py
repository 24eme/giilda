# -*- coding: iso-8859-1 -*
import sys, os, pandas as pd
from sqlalchemy import create_engine
engine = create_engine('sqlite:///'+sys.argv[1], echo=False, encoding='iso-8859-1')

if len(sys.argv) > 2:
    os.chdir(sys.argv[2])

if os.path.exists("export_bi_contrats.csv") and os.path.getsize("export_bi_contrats.csv"):
  try:
    sys.stderr.write("export_bi_contrats.csv\n")
    csv = pd.read_csv("export_bi_contrats.csv", encoding='iso-8859-1', delimiter=";", index_col=False).rename(columns={
       "#CONTRA": "type document", 'type de vente (VIN_VRAC, VIN_BOUTEILLE, RAISIN, MOUT)': 'type de vente', 'volume propose (en hl)': 'volume propose', 'volume enleve (en hl)': "volume enleve", 'prix unitaire (en hl)' : 'prix unitaire',
       'prix unitaire definitif (en hl)': 'prix unitaire definitif', 'prix variable (OUI, NON)': 'prix variable',
       'contrat interne (OUI, NON)': 'contrat interne', 'original (OUI, NON)' : 'original',
       'type de contrat(SPOT, PLURIANNUEL)' : "type de contrat",'type de produit (GENERIQUE, DOMAINE)': 'type de produit', 'nature de la cvo (MARCHE_DEFINITIF, COMPENSATION, NON_FINANCIERE, VINAIGRERIE)': 'nature de la cvo'})
    csv.to_sql('contrat', con=engine, if_exists='replace')
  except Exception as e:
    sys.stderr.write("ERROR: unable to read export_bi_contrats.csv:\n\t"+str(e)+"\n")

if os.path.exists("export_bi_drm.csv") and os.path.getsize("export_bi_drm.csv"):
    try:
        sys.stderr.write("export_bi_drm.csv\n")
        csv = pd.read_csv("export_bi_drm.csv", encoding='iso-8859-1', delimiter=";", index_col=False).rename(columns={"#DRM ID": "DRM ID", 'numéro archivage': 'numero archivage'})
        csv.to_sql('drm', con=engine, if_exists='replace')
    except Exception as e:
        sys.stderr.write("ERROR: unable to read export_bi_drm.csv:\n\t"+str(e)+"\n")

if os.path.exists("export_bi_mouvements.csv") and os.path.getsize("export_bi_mouvements.csv"):
    try:
        sys.stderr.write("export_bi_mouvements.csv\n")
        csv = pd.read_csv("export_bi_mouvements.csv", encoding='iso-8859-1', delimiter=";", index_col=False).rename(columns={'pays export (si export)': 'pays export', '#MOUVEMENT': "type de document"})
        csv.to_sql('mouvement', con=engine, if_exists='replace')
    except Exception as e:
        sys.stderr.write("ERROR: unable to read export_bi_mouvements.csv:\n\t"+str(e)+"\n")

if os.path.exists("export_bi_etablissements.csv") and os.path.getsize("export_bi_etablissements.csv"):
    try:
        sys.stderr.write("export_bi_etablissements.csv\n")
        csv = pd.read_csv("export_bi_etablissements.csv", encoding='iso-8859-1', delimiter=";", index_col=False).rename(columns={ 'statut (ACTIF, SUSPENDU)': 'statut', "#ETABLISSEMENT": "type de document"})
        csv.to_sql('etablissement', con=engine, if_exists='replace')
    except Exception as e:
        sys.stderr.write("ERROR: unable to read export_bi_etablissements.csv:\n\t"+str(e)+"\n")

if os.path.exists("export_bi_societes.csv") and os.path.getsize("export_bi_societes.csv"):
    try:
        sys.stderr.write("export_bi_societes.csv\n")
        csv = pd.read_csv("export_bi_societes.csv", encoding='iso-8859-1', delimiter=";", index_col=False).rename(columns={ 'statut (ACTIF, SUSPENDU)': 'statut', "#SOCIETE": "type de document"})
        csv.to_sql('societe', con=engine, if_exists='replace')
    except Exception as e:
        sys.stderr.write("ERROR: unable to read export_bi_societes.csv:\n\t"+str(e)+"\n")

if os.path.exists("export_bi_dss.csv") and os.path.getsize("export_bi_dss.csv"):
    try:
        sys.stderr.write("export_bi_dss.csv\n")
        csv = pd.read_csv("export_bi_dss.csv", encoding='iso-8859-1', delimiter=";", index_col=False).rename(columns={ 'statut (ACTIF, SUSPENDU)': 'statut', "#DS": "type de document"})
        csv.to_sql('ds', con=engine, if_exists='replace')
    except Exception as e:
        sys.stderr.write("ERROR: unable to read export_bi_dss.csv:\n\t"+str(e)+"\n")

if os.path.exists("export_bi_drm_stock.csv") and os.path.getsize("export_bi_drm_stock.csv"):
    try:
        sys.stderr.write("export_bi_drm_stock.csv\n")
        csv = pd.read_csv("export_bi_drm_stock.csv", encoding='iso-8859-1', delimiter=";", index_col=False).rename(columns={"#ID": "id stock"})
        csv.to_sql('DRM_Stock', con=engine, if_exists='replace')
    except Exception as e:
        sys.stderr.write("ERROR: unable to read export_bi_drm_stock.csv:\n\t"+str(e)+"\n")

if os.path.exists("external_drm_stock.csv") and os.path.getsize("external_drm_stock.csv"):
    try:
        sys.stderr.write("external_drm_stock.csv\n")
        csv = pd.read_csv("external_drm_stock.csv", encoding='iso-8859-1', delimiter=";", index_col=False)
        csv.to_sql('Stock DRM externes', con=engine, if_exists='replace')
    except Exception as e:
        sys.stderr.write("ERROR: unable to read external_drm_stock.csv:\n\t"+str(e)+"\n")

if os.path.exists("external_drm_mouvements.csv") and os.path.getsize("external_drm_mouvements.csv"):
    try:
        sys.stderr.write("external_drm_mouvements.csv\n")
        csv = pd.read_csv("external_drm_mouvements.csv", encoding='iso-8859-1', delimiter=";", index_col=False, dtype={'numero du contrat': 'str'})
        csv.to_sql('Mouvement DRM externes', con=engine, if_exists='replace')
    except Exception as e:
        sys.stderr.write("ERROR: unable to read external_drm_mouvements.csv:\n\t"+str(e)+"\n")
