#!/usr/bin/env bash
rm -rf fixtures
mkdir fixtures
mkdir fixtures/1
mkdir fixtures/2

php signature_gen_1862.php dir/1/SigGen.txt > fixtures/1/SigGen.yml
php signature_verify_1862.php dir/1/SigVer.rsp > fixtures/1/SigVer.yml
php keygeneration.php dir/1/KeyPair.rsp > fixtures/1/KeyPair.yml
php keyverification.php dir/1/PKV.rsp > fixtures/1/PKV.yml

php signature_gen_1864.php dir/2/SigGen.txt > fixtures/2/SigGen.yml
php signature_gen_1864.php dir/2/SigGen_TruncatedSHAs.txt > fixtures/2/SigGen_Truncated.yml
php signature_verify_1864.php dir/2/SigVer.rsp > fixtures/2/SigVer.yml
php keygeneration.php dir/2/KeyPair.rsp > fixtures/2/KeyPair.yml
php keyverification.php dir/2/PKV.rsp > fixtures/2/PKV.yml