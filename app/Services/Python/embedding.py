import os
import re
import pickle
import numpy as np
import torch
from transformers import BertJapaneseTokenizer, BertModel

np.set_printoptions(suppress=False, precision=2)


class Embedding:
    def __init__(self) -> None:
        self.embed = np.zeros((768, )).astype('f')
        pretrained_model = "cl-tohoku/bert-base-japanese-v2"

        self.tokenizer = BertJapaneseTokenizer.from_pretrained(pretrained_model)
        self.model = BertModel.from_pretrained(
            pretrained_model, # 日本語Pre trainedモデルの指定
            output_attentions = False, # アテンションベクトルを出力するか
            output_hidden_states = True, # 隠れ層を出力するか
        )

    def get(self, content):
        # for one_text in splitted_texts:
        tokens = np.array(self.tokenizer.encode(content))
        tokens_tensor = torch.tensor([tokens.tolist()])

        self.model.eval()
        with torch.no_grad(): # 勾配計算なし
            all_encoder_layers = self.model(tokens_tensor)["hidden_states"]

        # {0: 入力層, 11: 中間層最終層, 12: 出力層}を取得
        last_hidden_layer = all_encoder_layers[11]
        # 全ての単語トークンに対応する埋め込みの平均を取得
        embed = last_hidden_layer[0][1:-1].numpy().astype('f').mean(axis=0)
        embed = (embed*1000).astype(int)
        self.embed[...] = embed

        return self.embed


# バイナリーファイルから単語埋め込みインスタンスを生成（基本はこれを使う）
def embedding_instance():
    with open(f'{os.path.dirname(__file__)}/embed.binaryfile','rb') as f:
        model = pickle.load(f)
    return model


# 単語埋め込みの方式を変更したら実行
def reset_embedding():
    model = Embedding()
    with open(f'{os.path.dirname(__file__)}/embed.binaryfile', 'wb') as f:
        pickle.dump(model, f)
    return model
