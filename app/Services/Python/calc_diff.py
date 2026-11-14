import sys
import numpy as np
import difflib


def calc_diff(word1, word2):
    rate = difflib.SequenceMatcher(None, word1, word2).ratio()
    print(rate)
    # return rate


if __name__ == '__main__':
    word1, word2 = sys.argv[1].split()
    print(word2)
    calc_diff(word1, word2)
