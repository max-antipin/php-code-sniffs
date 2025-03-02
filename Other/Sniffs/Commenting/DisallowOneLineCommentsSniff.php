<?php
/**
 * This sniff prohibits the use of one-line comments.
 *
 * @category  PHP
 * @author    Max Antipin <max.v.antipin@gmail.com>
 */

namespace MaxAntipin\PHPCS\Standards\PSR12Ext\Sniffs\Commenting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

final class DisallowOneLineCommentsSniff implements Sniff
{
    public function register(): array
    {
        return [T_COMMENT];
    }

    public function process(File $phpcsFile, $stackPtr)
    {return;
        $tokens = $phpcsFile->getTokens();var_dump($tokens[$stackPtr]);
        if ($tokens[$stackPtr]['content'][0] === '#') {
            $error = 'One-line comments are prohibited; found %s';
            $data  = array(trim($tokens[$stackPtr]['content']));
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }

    }
}
