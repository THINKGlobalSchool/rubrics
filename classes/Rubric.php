<?
/**
 * Class defintion for a rubric
 * - Not sure if this is really necessary.. I was kind of a noob when I wrote this plugin
 *
 * @package rubrics
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

class Rubric extends ElggObject {

	// Useful constant
	// default size
	const NUM_ROWS = 4;
	const NUM_COLS = 6;

	// default headers
	const CRITERIA_HEADER = "rubrics:criteria:default";
	const LEVEL_1         = "rubrics:level1";
	const LEVEL_2         = "rubrics:level2";
	const LEVEL_3         = "rubrics:level3";
	const LEVEL_4         = "rubrics:level4";
	const LEVEL_5         = "rubrics:level5";

	/**
	 * Override the default access and the subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'rubric';
		$this->attributes['access_id'] = ACCESS_LOGGED_IN;
	}

	/**
	 * Returns the contents of the rubric
	 *
	 * @return type mixed Null on failure, array on success
	 */
	public function getContents() {
		if ($this->contents) {
			return unserialize($this->contents);
		} else {
			return null;
		}
	}

	/**
	 * Returns the default headers for a rubric.
	 *
	 * @return type array
	 */
	public static function getDefaultContent() {
		// this is returning a 2d array because the caller is expecting table data that's stored as
		// nested arrays.
		return array(
			// headers
			array(
				self::CRITERIA_HEADER,
				self::LEVEL_5,
				self::LEVEL_4,
				self::LEVEL_3,
				self::LEVEL_2,
				self::LEVEL_1
			),

			// criteria
			array('', '', '', '', ''),
			array('', '', '', '', ''),
			array('', '', '', '', ''),
			array('', '', '', '', ''),
		);
	}

	public function getNumRows() {
		if ($this->num_rows) {
			return $this->num_rows;
		} else {
			return self::NUM_ROWS;
		}
	}

	public function getNumCols() {
		if ($this->num_cols) {
			return $this->num_cols;
		} else {
			return self::NUM_COLS;
		}
	}

	// Setters
	public function setContents($value) {
		$this->contents = serialize($value);
	}

	public function setNumRows($value) {
		$this->num_rows = $value;
	}

	public function setNumCols($value) {
		$this->num_rows = $value;
	}

	/**
	 * Returns the URL for this rubric's history.
	 *
	 * @return string The history URL
	 */
	public function getHistoryURL() {
		return 'pg/rubrics/history/' . $this->getGUID()
			. '/' . elgg_get_friendly_title($this->title);
	}
}
