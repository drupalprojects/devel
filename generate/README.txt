GENERAL
===========================
To use these scripts, copy them to the root of your Drupal site.
When you are done, delete them since they are not access controlled.

To generate users, taxonomy terms, and content items (nodes and comments), you should 
use the Devel Generate module.

import-taxonomy-terms.php
===========================

This simple script creates terms out of an array of names which you provide. unlike
the other scripts in this directory, this one is meant for live sites which need to bulk
import data into the term table. See the source code comments for config instructions

update-teaser.php
=================

Use this script to regenerate the teasers in the node table.

import-users.php
==================

A small script that reads users from a csv formatted file and puts them into your database.
