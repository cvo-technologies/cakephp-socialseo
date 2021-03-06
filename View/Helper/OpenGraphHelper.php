<?php

App::uses('AppHelper', 'View/Helper');

/**
 * Class OpenGraphHelper
 *
 * @property HtmlHelper Html
 * @property SeoHelper Seo
 * @property TitleHelper Title
 */
class OpenGraphHelper extends AppHelper {

	public $helpers = array(
		'SocialSeo.Title', 'SocialSeo.Seo', 'Html'
	);

	/**
	 * @var string
	 */
	private $image = null;

	private $pageTypeMap = array(
		'page'    => 'website',
		'post'    => 'article',
		'profile' => 'profile'
	);

	/**
	 * @return string
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * @param string $image
	 */
	public function setImage($image) {
		$this->image = $image;
	}

	public function beforeLayout($layoutFile) {
		if ($this->Seo->getImage('open_graph')) {
			$this->setImage($this->Seo->getImage('open_graph'));
		}
	}

	public function getType() {
		if (isset($this->pageTypeMap[$this->Seo->getPageType()])) {
			return $this->pageTypeMap[$this->Seo->getPageType()];
		}

		return false;
	}

	public function fetch() {
		$this->Html->meta(
			array('property' => 'og:title', 'content' => $this->Title->getPageTitle()),
			null,
			array('inline' => false, 'block' => 'open_graph')
		);
		$this->Html->meta(
			array('property' => 'og:site_name', 'content' => $this->Title->getSiteTitle()),
			null,
			array('inline' => false, 'block' => 'open_graph')
		);
		if ($this->getType()) {
			$this->Html->meta(
				array('property' => 'og:type', 'content' => $this->getType()),
				null,
				array('inline' => false, 'block' => 'open_graph')
			);
		}
		if ($this->Seo->getDescription()):
			$this->Html->meta(
				array('property' => 'og:description', 'content' => $this->Seo->getDescription()),
				null,
				array('inline' => false, 'block' => 'open_graph')
			);
		endif;
		if ($this->getImage()) {
			$this->Html->meta(
				array('property' => 'og:image', 'content' => $this->getImage()),
				null,
				array('inline' => false, 'block' => 'open_graph')
			);
		}
		$this->Html->meta(
			array('property' => 'og:url', 'content' => $this->Seo->getCanonicalUrl()),
			null,
			array('inline' => false, 'block' => 'open_graph')
		);

		return $this->_View->fetch('open_graph');
	}

}