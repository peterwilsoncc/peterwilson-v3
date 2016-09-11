# peterwilson.cc v3

This is the repo for version 3 of peterwilson.cc. A new repo for a new site because I've learnt a tonne of things since the last redevelopment of my site so a new repo will help.

Also, while the redesign is going on having a seperate repo for staging sites, etc will sure be handy.

## Pre-requisites

* Git
* vagrant
* Virtual Box

## Local development

To clone this repository locally, run the following commands:

1. `git clone --recursive git@github.com:peterwilsoncc/peterwilson-v3.git pwcc-v3.local`
1. `cd pwcc-v3.local`
1. `git clone --recursive https://github.com/Chassis/Chassis.git chassis`
1. `cd chassis`
1. `cp ../bin/chassis.yaml config.local.yaml`
1. `vagrant up`
