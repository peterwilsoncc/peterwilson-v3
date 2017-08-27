#!/usr/bin/env bash

set -ex

cd ~
git clone git@github.com:peterwilsoncc/peterwilson-v3-compiled.git
pwd # Tech debt.
cd ~/peterwilson-v3-compiled
git checkout -B $CIRCLE_BRANCH
mv .git ~/_git
cd ~/$CIRCLE_PROJECT_REPONAME
rsync -rtu --delete --exclude-from='.rsyncexclude' --delete-excluded ./ ~/peterwilson-v3-compiled
cd -
mv ~/_git ./.git
git config user.email "auto-deploy@peterwilson.cc"
git config user.name "Auto deploy"
git add -A .
git diff --quiet && git diff --staged --quiet || git commit -am 'Deploy $CIRCLE_BRANCH - Build $CIRCLE_BUILD_NUM from $CIRCLE_SHA1'
git push -u origin $CIRCLE_BRANCH
