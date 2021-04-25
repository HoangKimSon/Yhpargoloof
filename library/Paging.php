<?php

/**
 * Paging.php
 * 
 * Paging library
 */

class Paging
{
	/**
	 * Create link for current page
	 *
	 * @param string $url base url.
	 * @param string $filter filter condition.
	 * 
	 * @return string new url with all parameters
	 *
	 */
	public function create_links($url, $filter = array())
	{
		$string = '';
		foreach ($filter as $key => $value) {
			$string .= "&{$key}={$value}";
		}

		return $url . (!empty($string) ? "?" . ltrim($string, "&") : "");
	}

	/**
	 * Create html template, start, limit 
	 *
	 * @param string $link current url.
	 * @param int $totalRecord total data in database.
	 * @param int $currentpage current page.
	 * @param int $limitRows item per page.
	 * 
	 * @return array['limit'] item per page
	 * @return array['start'] item start in database
	 * @return array['pageHtml'] html code for paging
	 *
	 */
	public function pagenations($link, $totalRecord, $currentpage, $limitRows)
	{
		// calculate start, limit
		$totalPage = ceil($totalRecord / $limitRows);
		if ($currentpage < 1) {
			$currentpage = 1;
		} elseif ($currentpage > $totalPage) {
			$currentpage = $totalPage;
		}
		$start = ($currentpage - 1) * $limitRows;

		// create html template
		$pageHtml = '';
		$pageHtml .= "<div class='text-center'>";
		$pageHtml .= "<nav aria-label='Page navigation'>";
		$pageHtml .= "<ul class='pagination'>";

		// create provious page button
		if ($currentpage > 1 && $currentpage <= $totalPage) {
			$lastPage = $currentpage - 1;
			$pageHtml .= "<li><a href='" . str_replace("page={$currentpage}", "page={$lastPage}", $link) . "'><span aria-hidden='true'>&laquo;</span></a></li>";
		}

		// create normal page
		for ($i = 1; $i <= $totalPage; $i++) {
			if ($currentpage == $i) { // if in current page, inactive user selection
				$pageHtml .= "<li class='active'><a>" . $i . "<span class='sr-only'></span></a></li>";
			} else { // if not current page
				$pageHtml .= "<li><a href='" . str_replace("page={$currentpage}", "page={$i}", $link) . "'>" . $i . "</a></li>";
			}
		}

		// create next page button
		if ($currentpage < $totalPage && $currentpage >= 1) {
			$nextPage = $currentpage + 1;
			$pageHtml .= "<li> <a href='" . str_replace("page={$currentpage}", "page={$nextPage}", $link) . "' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
		}

		$pageHtml .= "</ul>";
		$pageHtml .= "</nav>";
		$pageHtml .= "</div>";

		return array(
			'limit' => $limitRows,
			'start' => $start,
			'pageHtml' => $pageHtml
		);
	}
}
