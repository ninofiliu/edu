echo word to find: $1

IFS=$'\n'
for name in $(ls ./text); do
	echo +--------------- - - -
	echo '|' $name
	echo +--------------- - - -
	cat ./text/$name | grep -i -E $1
	echo
done
