#!/bin/bash

git pull
git status
cd plugins/acVinVracPlugin/
git push
COMMIT=$(git log | head -n1)
cd ../..
git add plugins/acVinVracPlugin
git commit -m "relatif au $COMMIT"
git push



