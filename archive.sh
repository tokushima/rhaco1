tar zcvf bin/rhaco1.tgz rhaco/
tar zcvf bin/rhaco1_test.tgz test/

TODAY=`date +%Y%m%d`
cp bin/rhaco1.tgz bin/rhaco1_$TODAY.tgz
cp bin/rhaco1_test.tgz bin/rhaco1_test_$TODAY.tgz
