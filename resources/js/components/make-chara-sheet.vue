<template>
  <div class="chara-sheet">
    <!-- ◆基本情報 -->
    <section class="section-basic">
      <h2>◆基本情報</h2>
      <p>名前：<input type="text"></p>
      <p>種族：
        <select v-model="race" @change="applyRaceBonus">
          <option value="human">只人</option>
          <option value="dwarf">鉱人</option>
          <option value="elf">森人</option>
          <option value="lizardman">蜥蜴人</option>
          <option value="halfling">圃人</option>
          <option value="darkElf">闇人</option>
          <option value="lycanthrope">獣憑き</option>
          <option value="martialBeastman">格闘獣人</option>
          <option value="bruteBeastman">剛力獣人</option>
          <option value="agileBeastman">俊敏獣人</option>
          <option value="sensoryBeastman">知覚獣人</option>
        </select>
        <select v-model="subRace">
          <option value="">なし</option>
          <option value="damphir">ダンピール</option>
          <option value="kruusnik">クルースニク</option>
          <option value="zduhachi">ズドゥハチ</option>
          <option value="human">只人</option>
          <option value="dwarf">鉱人</option>
          <option value="elf">森人</option>
          <option value="lizardman">蜥蜴人</option>
          <option value="halfling">圃人</option>
          <option value="darkElf">闇人</option>
          <option value="martialBeastman">格闘獣人</option>
          <option value="bruteBeastman">剛力獣人</option>
          <option value="agileBeastman">俊敏獣人</option>
          <option value="sensoryBeastman">知覚獣人</option>
        </select>
      </p>
      <p>性別：
        <select>
          <option value="male">男</option>
          <option value="female">女</option>
        </select>
      </p>
      <p>年齢：<input type="number" min="15" value="15" style="width: 45px;"></p>
      <p>経歴：{{ history1 }} ／ {{ history2 }} ／ {{ history3 }}</p>
      <!-- ◆経歴（2d6×3 → 割り振り） -->
      <section class="section-origin">
        <h2>◆経歴</h2>
      
        <!-- 経歴ダイスを振る -->
        <button @click="rollOriginDice">経歴ダイスを振る（2d6×3）</button>
      
        <div v-if="originDice.length">
          <p>出目：{{ originDice.join(', ') }}</p>
        
          <h3>割り振り</h3>
          <!-- 経歴① -->
          <label>経歴①：</label>
          <select v-model="assignedOrigin.origin1">
            <option v-for="d in originDice" :value="d" :disabled="isOriginAssigned(d) && assignedOrigin.origin1 !== d">
              {{ d }}
            </option>
          </select>
          <p>→ {{ origin1 }}</p>
        
          <!-- 経歴② -->
          <label>経歴②：</label>
          <select v-model="assignedOrigin.origin2">
            <option v-for="d in originDice" :value="d" :disabled="isOriginAssigned(d) && assignedOrigin.origin2 !== d">
              {{ d }}
            </option>
          </select>
          <p>→ {{ origin2 }}</p>
        
          <!-- 経歴③ -->
          <label>経歴③：</label>
          <select v-model="assignedOrigin.origin3">
            <option v-for="d in originDice" :value="d" :disabled="isOriginAssigned(d) && assignedOrigin.origin3 !== d">
              {{ d }}
            </option>
          </select>
          <p>→ {{ origin3 }}</p>
        </div>
      </section>
      <PhysicalFeatures/>
    </section>
    
    <!-- ◆冒険者情報 -->
    <section class="section-adventurer">
      <h2>◆冒険者レベル</h2>
      <p>冒険者レベル：{{ adventurerLevel }}</p>
      <p>職業レベル：{{ job1 }} ／ {{ job2 }} ／ {{ job3 }}</p>
      <p>等級：白磁級</p>
      <p>経験点：{{ exp }} 3000／3000 {{ expMax }}</p>
      <p>成長点：{{ growth }} 10／10 {{ growthMax }}</p>
    </section>

    <!-- ◆能力値 -->
    <section class="section-ability">
      <h2>◆能力値</h2>

      <button @click="rollDice">能力値をダイスロールする</button>


      <p>能力値合計：{{ totalAbility() }}</p>
      <h3>◆能力値マトリクス</h3>
      <table class="matrix-table">
        <tr>
          <th>能力</th>
          <th>体力{{ abilities.strength }}</th>
          <th>魂魄{{ abilities.spirit }}</th>
          <th>技量{{ abilities.dexterity }}</th>
          <th>知力{{ abilities.intellect }}</th>
        </tr>
        <tr>
          <td>集中度{{ abilities.concentration }}</td>
          <td>{{ abilities.strength + abilities.concentration }}</td>
          <td>{{ abilities.spirit + abilities.concentration }}</td>
          <td>{{ abilities.dexterity + abilities.concentration }}</td>
          <td>{{ abilities.intellect + abilities.concentration }}</td>
        </tr>
        <tr>
          <td>持久度{{ abilities.endurance }}</td>
          <td>{{ abilities.strength + abilities.endurance }}</td>
          <td>{{ abilities.spirit + abilities.endurance }}</td>
          <td>{{ abilities.dexterity + abilities.endurance }}</td>
          <td>{{ abilities.intellect + abilities.endurance }}</td>
        </tr>
        <tr>
          <td>反射度{{ abilities.reflex }}</td>
          <td>{{ abilities.strength + abilities.reflex }}</td>
          <td>{{ abilities.spirit + abilities.reflex }}</td>
          <td>{{ abilities.dexterity + abilities.reflex }}</td>
          <td>{{ abilities.intellect + abilities.reflex }}</td>
        </tr>
      </table>

      <div v-if="isBeginnerReliefAvailable()">
        <label>初心者救済：能力値を1つ3に補正</label>
        <select v-model="beginnerReliefTarget">
          <option v-for="(v, key) in abilities" :value="key">{{ key }}</option>
        </select>
        <button @click="checkBeginnerRelief">補正する</button>
      </div>

      <div v-if="!bonusApplied">
        <label>好きな能力値を+1：</label>
        <select v-model="bonusTarget">
          <option value="strength">体力点</option>
          <option value="spirit">魂魄点</option>
          <option value="dexterity">技量点</option>
          <option value="intellect">知力点</option>
        </select>
        <button @click="applyBonus">+1する</button>
      </div>
    </section>

    <!-- ◆派生ステータス -->
    <section class="section-derived">
      <h2>◆派生ステータス</h2>
      <p>生命力：{{ vitality }}</p>
      <p>生命力 ×2：{{ vitality2 }}</p>
      <p>移動力：{{ move }}</p>
      <p>呪文使用回数：{{ spellUses }}</p>
      <p>呪文抵抗基準値：{{ resistBase }}</p>
    </section>

    <!-- ◆パロメーターダイス -->
    <section class="section-parameter">
      <h2>◆パロメーターダイス</h2>

      <button @click="rollParameterDice">2d6 × 3 を振る</button>

      <div v-if="parameterDice.length">
        <p>出目：{{ parameterDice.join(', ') }}</p>
      </div>

      <h3>割り振り</h3>
      <label>生命力：</label>
      <select v-model="assignedParameter.vitality">
        <option v-for="d in parameterDice" :value="d" :disabled="isAssigned(d) && assignedParameter.vitality !== d">
          {{ d }}
        </option>
      </select>

      <label>移動力：</label>
      <select v-model="assignedParameter.move">
        <option v-for="d in parameterDice" :value="d" :disabled="isAssigned(d) && assignedParameter.move !== d">
          {{ d }}
        </option>
      </select>

      <label>呪文使用回数：</label>
      <select v-model="assignedParameter.spell">
        <option v-for="d in parameterDice" :value="d" :disabled="isAssigned(d) && assignedParameter.spell !== d">
          {{ d }}
        </option>
      </select>
    </section>
  </div>
</template>

<script>
import PhysicalFeatures from './PhysicalFeatures.vue';
export default {
  components: {
    PhysicalFeatures
  },
  data() {
    return {
      dice: Array(7).fill(1),// 出目（1〜3）
      abilities: {
        strength: 0,
        spirit: 0,
        dexterity: 0,
        intellect: 0,
        concentration: 0,
        endurance: 0,
        reflex: 0
      },
      race: '',
      subRace: '',
      raceBonusTable: { 
			  human: { strength: 1, spirit: 0, dexterity: 0, intellect: 1, concentration: 0, endurance: 2, reflex: 0 , moveBonus: 3 },
			  dwarf: { strength: 2, spirit: 0, dexterity: 2, intellect: -1, concentration: 1, endurance: 1, reflex: -1 , moveBonus: 2 },
			  elf: { strength: -1, spirit: 1, dexterity: 1, intellect: 1, concentration: 0, endurance: -1, reflex: 1 , moveBonus: 4 },
			  lizardman: { strength: 2, spirit: 1, dexterity: 0, intellect: 0, concentration: 1, endurance: 0, reflex: 0 , moveBonus: 2 },
			  halfling: { strength: -1, spirit: 2, dexterity: 1, intellect: 0, concentration: -1, endurance: 0, reflex: 2 , moveBonus: 3 },
			  darkElf: { strength: 0, spirit: 2, dexterity: 1, intellect: 1, concentration: 0, endurance: -1,  reflex: 1 , moveBonus: 3 },         // 闇人（ダークエルフ）
			  lycanthrope: { strength: 1, spirit: 0, dexterity: 1, intellect: 0, concentration: 0, endurance: 1, reflex: 0 , moveBonus: 3 },     // 獣憑き（ライカンスロープ）
			  martialBeastman: { strength: 2, spirit: 0, dexterity: 1, intellect: 0, concentration: 1, endurance: 0, reflex: -1 , moveBonus: 3 }, // 格闘獣人
			  bruteBeastman: { strength: 1, spirit: 1, dexterity: 0, intellect: 0, concentration: 1, endurance: 2, reflex: -1 , moveBonus: 2 },   // 剛力獣人
			  agileBeastman: { strength: 1, spirit: -1, dexterity: 1, intellect: 0, concentration: 0, endurance: 0, reflex: 1 , moveBonus: 4 },   // 俊敏獣人
			  sensoryBeastman: { strength: -1, spirit: 0, dexterity: 1, intellect: 1, concentration: 1, endurance: 0, reflex: 1 , moveBonus: 3 }  // 知覚獣人
		  },
      bonusTarget: '',
      beginnerReliefTarget: '',
      bonusApplied: false,
      reliefApplied: false,
      subRaceTable: {
        "": {
          label: "なし",
          useRaceBonus: true,
          useRaceOrigin: true
        },
        damphir: {
          label: "ダンピール",
          useRaceBonus: true,
          useRaceOrigin: true
        },
        kruusnik: {
          label: "クルースニク",
          useRaceBonus: true,
          useRaceOrigin: true
        },
        zduhachi: {
          label: "ズドゥハチ",
          useRaceBonus: true,
          useRaceOrigin: true
        },
        lycanthrope: {
          label: "獣憑き",
          useRaceBonus: false,   // 独自補正を使う
          useRaceOrigin: true    // 経歴①は本来の種族を使う
        }
      },
      originDice: [],

    // 割り振り先
    assignedOrigin: {
      origin1: null,
      origin2: null,
      origin3: null
    },

    // 経歴結果
      origin1: "",
      origin2: "",
      origin3: "",
      // 経歴表示用
      history1: "",
      history2: "",
      history3: "",
      raceOriginTable: {
        human: {
          origin1: {
            2: "冒険者",
            3: "無頼",
            4: "猟師",
            5: "学者",
            6: "職人",
            7: "農民",
            8: "商人",
            9: "兵士",
            10: "騎士",
            11: "神官",
            12: "貴族"
          }
        },
        dwarf: {
          origin1: {
            2: "冒険者",
            3: "無頼",
            4: "猟師",
            5: "商人",
            6: "亭主",
            7: "鍛冶師",
            8: "職人",
            9: "兵士",
            10: "盾砕き",
            11: "神官",
            12: "貴族"
          }
        },
        elf: {
          origin1: {
            2: "冒険者",
            3: "無頼",
            4: "半森人",
            5: "細工師",
            6: "兵士",
            7: "猟師",
            8: "詩人",
            9: "楽師",
            10: "語り部",
            11: "祈祷師",
            12: "族長"
          }
        },
        lizardman: {
          origin1: {
            2: "冒険者",
            3: "無頼",
            4: "奴隷",
            5: "騎兵",
            6: "工兵",
            7: "弓兵",
            8: "槍兵",
            9: "歩兵",
            10: "軍師",
            11: "司祭",
            12: "王卵"
          }
        },
        halfling: {
          origin1: {
            2: "冒険者",
            3: "無頼",
            4: "詩人",
            5: "学者",
            6: "職人",
            7: "農民",
            8: "商人",
            9: "亭主",
            10: "庭師",
            11: "騎士",
            12: "地主"
          }
        },
        darkElf: {
          origin1: {
            2: "冒険者",
            3: "異端者",
            4: "犯罪者",
            5: "密偵",
            6: "商家",
            7: "兵士",
            8: "拷問吏",
            9: "武人",
            10: "神官",
            11: "精霊使い",
            12: "貴種"
          }
        },
        martialBeastman: {
          origin1: {
            2: "冒険者",
            3: "無頼",
            4: "奴隷",
            5: "占い師",
            6: "労働者",
            7: "狩人",
            8: "傭兵",
            9: "兵士",
            10: "商人",
            11: "自然崇拝者",
            12: "族長"
          }
        },
        bruteBeastman: {
                  origin1: {
                    2: "冒険者",
                    3: "無頼",
                    4: "奴隷",
                    5: "占い師",
                    6: "労働者",
                    7: "狩人",
                    8: "傭兵",
                    9: "兵士",
                    10: "商人",
                    11: "自然崇拝者",
                    12: "族長"
                  }
              },
        agileBeastman: {
                  origin1: {
                    2: "冒険者",
                    3: "無頼",
                    4: "奴隷",
                    5: "占い師",
                    6: "労働者",
                    7: "狩人",
                    8: "傭兵",
                    9: "兵士",
                    10: "商人",
                    11: "自然崇拝者",
                    12: "族長"
                  }
              },
        sensoryBeastman: {
                  origin1: {
                    2: "冒険者",
                    3: "無頼",
                    4: "奴隷",
                    5: "占い師",
                    6: "労働者",
                    7: "狩人",
                    8: "傭兵",
                    9: "兵士",
                    10: "商人",
                    11: "自然崇拝者",
                    12: "族長"
                  }
                }
              },
      // 経歴②・③は全種族共通
      commonOriginTable: {
        origin2: {
          2: "奴隷",
          3: "牢獄",
          4: "戦場",
          5: "神殿",
          6: "孤児",
          7: "平穏",
          8: "貧困",
          9: "学校",
          10: "箱入り",
          11: "贅沢",
          12: "宮廷"
        },
        origin3: {
          2: "宿敵",
          3: "上司",
          4: "後輩",
          5: "取引相手",
          6: "部下",
          7: "家族",
          8: "親友",
          9: "先輩",
          10: "婚約者",
          11: "好敵手",
          12: "師匠"
        }
      },
      parameterDice: [], // 2d6 を3回振った結果
      assignedParameter: {
        vitality: null,
        move: null,
        spell: null
      },
      adventurerLevel: 1,
      spellSkillBonus: 0,
      skillBonus: 0   // 
    };
  },
  computed: {
    vitality() {
      const base = this.abilities.strength + this.abilities.spirit + this.abilities.endurance;
      const dice = this.assignedParameter.vitality || 0;
      return base + dice;
    },

    vitality2() {
      return this.vitality * 2;
    },

    move() {
      const dice = this.assignedParameter.move || 0;
      const bonus = this.raceBonusTable[this.race]?.moveBonus || 1;
      return dice * bonus;
    },

    spellUses() {
      const dice = this.assignedParameter.spell || 0;
      let base = 0;
      if (dice <= 6) base = 0;
      else if (dice <= 9) base = 1;
      else if (dice <= 11) base = 2;
      else base = 3;
      return base + this.spellSkillBonus;
    },
    resistBase() {
      return this.abilities.spirit + this.abilities.reflex + this.adventurerLevel + this.skillBonus;
    }
  },
    watch: {
      'assignedOrigin.origin1'(val) {
      if (val) {
        const baseRace = this.subRace || this.race;
        this.origin1 = this.raceOriginTable[baseRace].origin1[val];
        this.history1 = this.origin1;
      }
    },
    'assignedOrigin.origin2'(val) {
      if (val) {
        this.origin2 = this.commonOriginTable.origin2[val];
        this.history2 = this.origin2;
      }
    },
    'assignedOrigin.origin3'(val) {
      if (val) {
        this.origin3 = this.commonOriginTable.origin3[val];
        this.history3 = this.origin3;
      }
    }
  },
  methods: {
      rollDice() {
        const results = Array.from({ length: 7 }, () => Math.floor(Math.random() * 3) + 1);
        this.dice = results;

        const keys = Object.keys(this.abilities);
        keys.forEach((key, index) => {
          this.abilities[key] = results[index];
        });
      
        // ★ 種族が選ばれているなら補正を再適用
        if (this.race) {
          this.applyRaceBonus();
        }
    },
    totalAbility() {
        return Object.values(this.abilities).reduce((sum, val) => sum + val, 0);
      },
      isBeginnerReliefAvailable() {
        return this.totalAbility() <= 15 && !this.reliefApplied;
    },
    applyRaceBonus() {
        // 追加種族の仕様
        const subRaceTable = {
          "": { useRaceBonus: true },
          damphir: { useRaceBonus: true },
          kruusnik: { useRaceBonus: true },
          zduhachi: { useRaceBonus: true },
          lycanthrope: { useRaceBonus: false } // 独自補正を使う
        };

        const sub = subRaceTable[this.subRace];

        // 種族補正に使う種族を決定
        let bonusRace = this.race;

        // 獣憑きだけは独自補正を使う
        if (sub && sub.useRaceBonus === false) {
          bonusRace = "lycanthrope";
        }
    
        const bonus = this.raceBonusTable[bonusRace];
        if (!bonus) return;
        
        const keys = Object.keys(this.abilities);
        
        // abilities を dice に戻す
        keys.forEach((key, index) => {
          this.abilities[key] = this.dice[index];
        });
      
        // 種族補正を加算
        keys.forEach((key) => {
          this.abilities[key] += bonus[key] || 0;
        });
      },    
    checkBeginnerRelief() {
      if (this.isBeginnerReliefAvailable() && this.beginnerReliefTarget) {
      
        // abilities のキー一覧を取得
        const keys = Object.keys(this.abilities);

        // 対象の能力値が dice の何番目かを調べる
        const index = keys.indexOf(this.beginnerReliefTarget);

        // 出目を 3 に補正
        this.dice[index] = 3;

        // abilities を dice から再計算
        keys.forEach((key, i) => {
          this.abilities[key] = this.dice[i];
        });
        this.reliefApplied = true;
        // ★ 種族補正を再適用
        if (this.race) {
          this.applyRaceBonus();
        }
      }
    },

    applyBonus() {
      if (this.bonusTarget && !this.bonusApplied) {
        this.abilities[this.bonusTarget]++;
        this.bonusApplied = true;
      }
    },
    isAssigned(diceValue) {
      return Object.values(this.assignedParameter).includes(diceValue);
    },
    rollOriginDice() {
      this.originDice = [
        Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1,
        Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1,
        Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1
      ];

        // 経歴①は subRace が本来の種族になる
        const baseRace = this.subRace || this.race;
        const d = this.originDice[0]; // 1つ目の出目を使う
        this.origin1 = this.raceOriginTable[baseRace].origin1[d];
        this.history1 = this.origin1;
    },
    isOriginAssigned(d) {
      return Object.values(this.assignedOrigin).includes(d);
    },

    rollParameterDice() {
      this.parameterDice = [
        Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1,
        Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1,
        Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1
      ];
    }
  }
};
</script>