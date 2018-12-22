<?php
/**
 * Interface for checking authority.
 *
 * @author		H.Chihoon
 * @copyright	2018 DesignAndDevelop
 */

namespace Povium\Http\Middleware;

interface AuthorityCheckerInterface
{
    /**
     * Check if satisfy the required authority level.
     */
    public function checkAuthority();
}