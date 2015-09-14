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

			/* Remove stop words */
			$commonWords = array('a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also', 'although', 'always', 'am', 'among', 'amongst', 'amoungst', 'amount', 'an', 'and', 'another', 'any', 'anyhow', 'anyone', 'anything', 'anyway', 'anywhere', 'are', 'around', 'as', 'at', 'back', 'be', 'became', 'because', 'become', 'becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom', 'but', 'by', 'call', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven', 'else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own', 'part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the');
			$string = strtolower(preg_replace('/\b(' . implode('|', $commonWords) . ')\b/', '', LanguageProcessor::normaliseEmojis($string)));

			return (float)$this->classifier->classify($string);
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
					$word = preg_replace('/[^A-Za-z0-9]/', '', strtolower(LanguageProcessor::normaliseEmojis($word)));
					$this->source->addDocument('' . $key, '' . $word);
				}
			}

			$this->source->write();

			return true;

		}

//		public function addToIndex($sentiment, $word) {
//			$word = preg_replace('/[^A-Za-z0-9]/', '', strtolower(LanguageProcessor::normaliseEmojis($word)));
//			$this->source->addDocument('' . $sentiment, '' . $word);
//		}
//
//		public function wipeIndex() {
//			return $this->source->wipe();
//		}
//
//		public function saveIndex() {
//			$this->source->write();
//		}

	}

?>
