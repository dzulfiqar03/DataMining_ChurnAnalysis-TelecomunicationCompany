#!/bin/bash

echo "--- Menjalankan Git Operations ---"

git add .

input_branch="Masukkan branch Anda: "
echo $input_branch
read -r input_branch

git checkout "$input_branch"
echo "Berada di branch $input_branch"

input_commit="Enter commit message: "
echo $input_commit
read -r input_commit
git commit -m "$input_commit"

git push origin "$input_branch"

echo "Pushed to develop $input_branch"

# Run stop.sh to stop services
sh ./stop.sh
echo "--- Git Operations Completed ---"
exit 0 