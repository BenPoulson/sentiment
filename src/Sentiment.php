<?php

	namespace BenPoulson\Sentiment;

	use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;
	use Camspiers\StatisticalClassifier\Model\Model;

	class Sentiment {

		private $model;
		private $source;
		private $classifier;

		const CORPUS_PATH = __DIR__ . '/../assets/corpus.json';

		public function __construct(Cache\SentimentCache $source) {
			$this->model = new Model();
			$this->source = $source;
			$this->classifier = new ComplementNaiveBayes($this->source, $this->model);
		}

		/**
		 * Assess the sentiment of a given string
		 * @param $string String to be classified
		 * @return float Sentiment scale between -10 and +10
		 */
		public function classify($string) {
			return (float) $this->classifier->classify($string);
		}

		/**
		 * Rebuild the sentiment index using the provided corpus
		 * @return bool If the index rebuilt sucessfully
		 */
		public function rebuildIndex() {

			if(!file_exists(self::CORPUS_PATH)) {
				return false;
			}

			$corpusFile = file_get_contents(self::CORPUS_PATH);
			$corpusData = json_decode($corpusFile, true);

			if(!$corpusData) {
				return false;
			}

			$this->source->wipe();

			foreach ($corpusData as $key => $words) {
				foreach($words as $word) {
					$word = preg_replace('/[^A-Za-z]/', '', LanguageProcessor::normaliseEmojis(strtolower($word)));
					$this->source->addDocument('' . $key, '' . $word);
				}
			}

			$this->source->write();

			return true;

		}
	}

?>
