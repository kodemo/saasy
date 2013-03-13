<?php

namespace saasy;

/**
 * Contains the logic around managing organizations.
 *
 * Fields:
 *
 * - id - Auto-incrementing ID for each organzation row
 * - name - Organization name
 * - subdomain - Subdomain to link to this organization
 * - status - Status of the organization
 *
 * Subdomains must be unique, and cannot be 'www'.
 *
 * Status may be used to enable/disable features for an organization.
 */
class Organization extends \Model {
	public $table = '#prefix#saasy_org';

	/**
	 * The output for the header of the site.
	 */
	public static function header () {
		$org = App::org ();
		if (! $org) {
			return App::name ();
		}

		$logo = $org->logo ();

		if ($logo !== false) {
			return sprintf (
				'<img src="%s" title="%s" />',
				$logo,
				$org->name
			);
		}
		return $org->name;
	}

	/**
	 * Get the full domain for the current organzation.
	 */
	public function domain () {
		$parts = explode ('.', $_SERVER['HTTP_HOST']);
		if (count ($parts === 3)) {
			array_shift ($parts);
		}
		return $this->subdomain . '.' . join ('.', $parts);
	}

	/**
	 * Returns a correctly sized logo if available. If not, returns false.
	 */
	public function logo ($width = 250, $height = 40) {
		$files = glob ('cache/saasy/logos/' . $this->id . '.{jpg|png|gif}', GLOB_BRACE);
		if (count ($files) > 0) {
			$logo = array_shift ($files);
			return '/' . \Image::resize ($logo, $width, $height, 'cover');
		}
		return false;
	}
}

?>