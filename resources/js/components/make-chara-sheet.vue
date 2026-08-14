<template>
  <div class="chara-sheet">
    <!-- ◆基本情報 -->
    <section class="section-basic">
      <h2>◆基本情報</h2>
      <p>名前：{{ name }}</p>
      <p>種族：{{ race }}
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
        </select></p>
      <p>性別：{{ gender }}</p>
      <p>年齢：{{ age }}</p>
      <p>経歴：{{ history1 }} ／ {{ history2 }} ／ {{ history3 }}</p>
      <p>身体的特徴：{{ bodyFeature }}</p>
    </section>
    
    <!-- ◆冒険者情報 -->
    <section class="section-adventurer">
      <h2>◆冒険者レベル</h2>
      <p>冒険者レベル：{{ adventurerLevel }}</p>
      <p>職業レベル：{{ job1 }} ／ {{ job2 }} ／ {{ job3 }}</p>
      <p>等級：白磁級</p>
      <p>経験点：{{ exp }} ／ {{ expMax }}</p>
      <p>成長点：{{ growth }} ／ {{ growthMax }}</p>
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
export default {
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
      const bonus = this.raceBonusTable[this.race];
      if (!bonus) return;

      const keys = Object.keys(this.abilities);

      // ① まず abilities を dice から復元（素の出目に戻す）
      keys.forEach((key, index) => {
        this.abilities[key] = this.dice[index];
      });
    
      // ② その上に種族補正を加算
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