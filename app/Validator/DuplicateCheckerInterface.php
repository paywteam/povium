<?php
/**
* Interface for duplicate checker.
*
* @author		H.Chihoon
* @copyright	2019 Payw
*/

namespace Povium\Validator;

interface DuplicateCheckerInterface
{
	/**
	 * Duplicate check.
	 *
	 * @param mixed	$input
	 *
	 * @return bool
	 */
	public function isAlreadyTaken($input);
}
