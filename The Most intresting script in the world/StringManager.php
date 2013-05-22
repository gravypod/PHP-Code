<?php

	class StringManager
	{

		public $longestString;

		public $lines = array();

		public function StringManager($quote)
		{

			$tempString = "";
			$stringLength = strlen($quote);

			for ($i = 0; $i < $stringLength; $i++) {

				$tempString .= $quote[$i];

				$lastCharacter = $tempString[strlen($tempString) - 1]; // find the last char of the temp str

				if ($lastCharacter === ",") { // Check for places to break
					$this->addNextLine($tempString);
				} else if ($lastCharacter === "." and !(isset($quote[$i + 1]) and $quote[$i + 1] === "\"")) { // Keep quotes intact
					$this->addNextLine($tempString);
				}

			}

			if ($tempString == ".") { // account for any last/un-pushed strings
				$this->lines[count($this->lines)] = trim($tempString);
			} else {
				$this->addNextLine($tempString);
			}


		}

		private function addNextLine(&$line) {
			$this->lines[count($this->lines) + 1] = trim($line);
			$line = "";
		}

		public function getLines()
		{
			return $this->lines;
		}

	}