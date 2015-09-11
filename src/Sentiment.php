<?php

	namespace BenPoulson\Sentiment;

	use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;
	use Camspiers\StatisticalClassifier\Model\Model;

	class Sentiment {

		private $model;
		private $source;
		private $classifier;

		public function __construct(Cache\SentimentCache $source) {
			$this->model = new Model();
			$this->source = $source;
			$this->classifier = new ComplementNaiveBayes($this->source, $this->model);
		}

		public function rebuildIndex() {

			$corpus = [
				'preventing' => -0.6,
				'prizefight' => -0.4,
				'prizefighters' => -0.4,
				'repressurizing' => -0.4,
				'richweeds' => -0.4,
				'screwworms' => -0.4,
				'sentenced' => -0.4,
				'sillibub' => -0.4,
				'stealthy' => -0.4,
				'stinkwood' => -0.4,
				'suretyship' => -0.4,
				'teasel' => -0.4,
				'teasels' => -0.4,
				'tenderfoot' => -0.1,
				'thwarted' => -0.1,
				'tranquilizer' => -0.1,
				'tranquilizes' => -0.1,
				'tranquillizer' => -0.1,
				'trembles' => -0.1,
				'unbiased' => -0.1,
				'warsaw' => -0.1,
				'wellhole' => -0.1,
				'winnower' => -0.1,
				'winnowing' => -0.1,
				'wiseacres' => -0.1,
				'wisecrack' => -0.1,
				'wisecracker' => -0.1,
			];

			foreach($corpus as $key => $sentiment){
				$this->source->addDocument((String) $sentiment, (String) $key);
			}

			return $classifier->classify('today is screwworms');

		}
	}

?>
