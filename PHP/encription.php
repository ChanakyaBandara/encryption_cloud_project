<?php

use Defuse\Crypto\Exception as Ex;
use Defuse\Crypto\File;

function encryptFileWithKey($input_path, $output_path, $key){
    try {
        File::encryptFileWithPassword($input_path, $output_path, $key);
    } catch (Ex\IOException $ex) {
        // TODO: But that's not what this exception means!
        echo "There was a file I/O error.";
        return false;
    }
    return true;
}

function decryptFileWithKey($input_path, $output_path, $key){
    try {
        File::decryptFileWithPassword($input_path, $output_path, $key);
    } catch (Ex\WrongKeyOrModifiedCiphertextException $ex) {
        echo "Either you're trying to decrypt with the wrong password, or the encrypted file
has been changed since it was first created. The changes might have been made by
someone trying to attack your security, so we will not proceed decrypting the
file.";
        return false;
    } catch (Ex\IOException $ex) {
        // TODO: But that's not what this exception means!
        echo "There was a file I/O error.";
        return false;
    }

    return true;
}
