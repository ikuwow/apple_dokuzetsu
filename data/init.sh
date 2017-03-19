#!/bin/bash

thispath=$(cd $(dirname ${BASH_SOURCE:-$0}); pwd)

cat ${thispath}/schema.sql | sqlite3 ${thispath}/apple_dokuzetsu.sqlite
