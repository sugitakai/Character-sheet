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
          <section class="sheet-section">

      <h2>◆冒険者技能</h2>

      <table class="skill-table">

        <thead>
          <tr>
            <th>技能名</th>
            <th>初歩</th>
            <th>習熟</th>
            <th>熟達</th>
            <th>達人</th>
            <th>伝説</th>
            <th>効果</th>
          </tr>
        </thead>

        <tbody>

          <tr v-for="n in 9" :key="'adventure-skill-' + n">

            <td>
              <input
                type="text"
                placeholder="技能名"
              >
            </td>

            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>

            <td>
              <input
                type="text"
                placeholder="効果"
              >
            </td>

          </tr>

        </tbody>

      </table>

    </section>


    <!-- ==================================================
         ◆ 一般技能
    ================================================== -->
    <section class="sheet-section">

      <h2>◆一般技能</h2>

      <table class="skill-table">

        <thead>
          <tr>
            <th>技能名</th>
            <th>初歩</th>
            <th>習熟</th>
            <th>熟達</th>
            <th>効果</th>
          </tr>
        </thead>

        <tbody>

          <tr v-for="n in 10" :key="'general-skill-' + n">

            <td>
              <input
                type="text"
                placeholder="技能名"
              >
            </td>

            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>
            <td><input type="checkbox"></td>

            <td>
              <input
                type="text"
                placeholder="効果"
              >
            </td>

          </tr>

        </tbody>

      </table>

    </section>


    <!-- ==================================================
         ◆ 呪文
    ================================================== -->
    <section class="sheet-section">

      <h2>◆呪文</h2>

      <p>
        呪文行使基本値
        （知力集中or魂魄集中+技能補正）：
        <strong>【 {{ spellCastBase }} 】</strong>
      </p>

      <p>
        呪文維持基本値
        （知力持久or魂魄持久+技能補正）：
        <strong>【 {{ spellMaintainBase }} 】</strong>
      </p>

      <p>
        呪文LV：
        真言【 {{ spellLevels.word }} 】
        死霊【 {{ spellLevels.necromancy }} 】
        奇跡【 {{ spellLevels.miracle }} 】
        祖竜【 {{ spellLevels.ancientDragon }} 】
        精霊【 {{ spellLevels.spirit }} 】
      </p>

      <h3>●習得呪文</h3>

      <div
        v-for="n in 3"
        :key="'spell-' + n"
        class="entry-row"
      >
        <input
          type="text"
          placeholder="呪文名（読み）"
        >

        <span>：</span>

        <input
          type="text"
          placeholder="参照P"
          class="page-input"
        >
      </div>


      <h3>●習得武技</h3>

      <div
        v-for="n in 6"
        :key="'martial-' + n"
        class="entry-row"
      >
        <input
          type="text"
          placeholder="武技名（読み）"
        >

        <span>：</span>

        <input
          type="text"
          placeholder="参照P"
          class="page-input"
        >
      </div>

    </section>


    <!-- ==================================================
         ◆ 攻撃
    ================================================== -->
    <section class="sheet-section">

      <h2>◆攻撃</h2>

      <p>
        命中基準値（技量集中）：
        <strong>【 {{ attackBase }} 】</strong>
      </p>

      <p>
        職業修正
        近接：【 {{ jobMelee }} 】
        弩弓：【 {{ jobCrossbow }} 】
        投擲：【 {{ jobThrow }} 】
      </p>

      <p>
        技能修正
        近接：【 {{ skillMelee }} 】
        弩弓：【 {{ skillCrossbow }} 】
        投擲：【 {{ skillThrow }} 】
      </p>


      <h3>●武器</h3>

      <div
        v-for="n in 3"
        :key="'weapon-' + n"
        class="equipment-box"
      >

        <input
          type="text"
          placeholder="道具名（読み）"
          class="equipment-name"
        >

        <p>
          用途／属性／射程：
          <input type="text">
          ／
          <input type="text">
          ／
          <input type="text">
        </p>

        <p>
          命中修正：
          <input type="number" value="0">

          命中値：
          <strong>--</strong>
        </p>

        <p>
          基本ダメージ：
          <input type="text">

          ／職業修正：
          <input type="number" value="0">
        </p>

        <p>
          合計ダメージ：
          <strong>--</strong>
        </p>

        <p>
          効果：
          <input type="text">

          ／参照P：
          <input type="text" class="page-input">
        </p>

      </div>


      <h3>●効力値</h3>

      <p>
        0〜14：変化無し　
        15〜19：+1D6　
        20〜24：+2D6　
        25〜29：+3D6　
        30〜39：+4D6　
        40〜：+5D6
      </p>

    </section>


    <!-- ==================================================
         ◆ 防御
    ================================================== -->
    <section class="sheet-section">

      <h2>◆防御</h2>

      <p>
        回避基準値：
        <strong>【 {{ evadeBase }} 】</strong>
      </p>

      <p>
        盾受け基準値：
        <strong>【 {{ shieldBase }} 】</strong>
      </p>

      <p>
        隠密基準値：
        <strong>【 {{ stealthBase }} 】</strong>
      </p>


      <h3>●鎧</h3>

      <div class="equipment-box">

        <input
          type="text"
          placeholder="道具名（読み）"
          class="equipment-name"
        >

        <p>
          属性：
          <input type="text">

          装甲値：
          <input type="number" value="0">
        </p>

        <p>
          回避値補正：
          <input type="number" value="0">

          移動力修正：
          <input type="number" value="0">
        </p>

        <p>
          隠密性：
          <input type="text">
          ／
          <input type="number" value="0">
        </p>

        <p>
          技能修正：
          <input type="number" value="0">
        </p>

        <p>
          効果：
          <input type="text">

          ／参照P：
          <input type="text" class="page-input">
        </p>

      </div>


      <h3>●盾</h3>

      <div class="equipment-box">

        <input
          type="text"
          placeholder="道具名（読み）"
          class="equipment-name"
        >

        <p>
          属性：
          <input type="text">

          盾受け修正：
          <input type="number" value="0">
        </p>

        <p>
          盾受け値：
          <strong>--</strong>
        </p>

        <p>
          隠密性：
          <input type="text">
          ／
          <input type="number" value="0">
        </p>

        <p>
          技能修正：
          <input type="number" value="0">
        </p>

        <p>
          効果：
          <input type="text">

          ／参照P：
          <input type="text" class="page-input">
        </p>

      </div>


      <p>
        所持品移動力修正合計：
        <strong>【 {{ itemMovePenalty }} 】</strong>
      </p>

      <div class="defense-total">

        <p>
          移動力合計：
          <strong>【 {{ moveTotal }} 】</strong>
        </p>

        <p>
          回避値合計：
          <strong>【 {{ evadeTotal }} 】</strong>
        </p>

        <p>
          隠密性合計：
          <strong>【 {{ stealthTotal }} 】</strong>
        </p>

        <p>
          装甲値合計：
          <strong>【 {{ armorTotal }} 】</strong>
        </p>

        <p>
          盾受け基準値合計：
          <strong>【 {{ shieldTotal }} 】</strong>
        </p>

        <p>
          装甲値+盾受け値合計：
          <strong>【 {{ armorShieldTotal }} 】</strong>
        </p>

      </div>

    </section>


    <!-- ==================================================
         ◆ 移動妨害
    ================================================== -->
    <section class="sheet-section">

      <h2>◆移動妨害</h2>

      <p>
        体力反射：
        <strong>【 {{ movementStrengthReflex }} 】</strong>

        戦士：
        <strong>【 {{ movementWarrior }} 】</strong>

        修正：
        <strong>【 {{ movementModifier }} 】</strong>
      </p>

      <p>
        基準値：
        <strong>【 {{ movementBase }} 】</strong>
      </p>

      <p>
        移動妨害への抵抗
        体力or技量集中：
        <strong>【 {{ movementResistAbility }} 】</strong>
      </p>

      <p>
        戦士or武道家or斥候：
        <strong>【 {{ movementResistJob }} 】</strong>
      </p>

      <p>
        基準値：
        <strong>【 {{ movementResistBase }} 】</strong>
      </p>

    </section>


    <!-- ==================================================
         ◆ 所持品
    ================================================== -->
    <section class="sheet-section">

      <h2>◆所持金</h2>

      <p>
        銀貨：
        <input
          type="number"
          v-model="money"
        >
        枚
      </p>


      <h3>●所持品</h3>

      <div class="inventory-grid">

        <div
          v-for="n in 18"
          :key="'item-' + n"
        >
          <input
            type="text"
            placeholder="所持品"
          >

          <input
            type="text"
            placeholder="参照P"
            class="page-input"
          >
        </div>

      </div>


      <h3>●その他の所持品</h3>

      <textarea
        v-model="otherItems"
        rows="5"
        placeholder="その他の所持品"
      ></textarea>

    </section>
    </section>
  </div>
</template>

<script>
import PhysicalFeatures from './PhysicalFeatures.vue';
import { raceBonusTable } from '@/data/raceBonusTable';
import { subRaceTable } from '@/data/subRaceTable';
import { raceOriginTable, commonOriginTable } from '@/data/originTable';
import { rollDice, rollOriginDice, rollParameterDice } from '@/services/DiceService';
import {
  calculateVitality,
  calculateVitality2,
  calculateMove,
  calculateSpellUses,
  calculateResistBase
} from '@/rules/CharacterCalculator';
export default {
  components: {
    PhysicalFeatures
  },
  data() {
    return {
      raceBonusTable,
      subRaceTable,
      raceOriginTable,
      commonOriginTable,
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

      bonusTarget: '',
      beginnerReliefTarget: '',
      bonusApplied: false,
      reliefApplied: false,

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

      // 経歴②・③は全種族共通

      parameterDice: [], // 2d6 を3回振った結果
      assignedParameter: {
        vitality: null,
        move: null,
        spell: null
      },
      adventurerLevel: 1,
      spellSkillBonus: 0,
      money: 100,
      otherItems: '',

      spellLevels: {
        word: 0,
        necromancy: 0,
        miracle: 0,
        ancientDragon: 0,
        spirit: 0
      },
      skillBonus: 0 ,  // 
      spellCastBase: 0,
      spellMaintainBase: 0,

      attackBase: 0,

      jobMelee: 0,
      jobCrossbow: 0,
      jobThrow: 0,

      skillMelee: 0,
      skillCrossbow: 0,
      skillThrow: 0,

      evadeBase: 0,
      shieldBase: 0,
      stealthBase: 0,

      itemMovePenalty: 0,

      moveTotal: 0,
      evadeTotal: 0,
      stealthTotal: 0,
      armorTotal: 0,
      shieldTotal: 0,
      armorShieldTotal: 0,

      movementStrengthReflex: 0,
      movementWarrior: 0,
      movementModifier: 0,
      movementBase: 0,

      movementResistAbility: 0,
      movementResistJob: 0,
      movementResistBase: 0,
    };
  },
computed: {
  vitality() {
    return calculateVitality(
      this.abilities,
      this.assignedParameter
    );
  },

  vitality2() {
    return calculateVitality2(this.vitality);
  },

  move() {
    return calculateMove(
      this.assignedParameter,
      this.raceBonusTable,
      this.race
    );
  },

  spellUses() {
    return calculateSpellUses(
      this.assignedParameter,
      this.spellSkillBonus
    );
  },

  resistBase() {
    return calculateResistBase(
      this.abilities,
      this.adventurerLevel,
      this.skillBonus
    );
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
      const results = rollDice();

      this.dice = results;

      const keys = Object.keys(this.abilities);

      keys.forEach((key, index) => {
        this.abilities[key] = results[index];
      });

      // 種族が選ばれているなら補正を再適用
      if (this.race) {
        this.applyRaceBonus();
      }
    },

    rollOriginDice() {
        this.originDice = rollOriginDice();
    
        const baseRace = this.subRace || this.race;
        const d = this.originDice[0];
    
        console.log("race =", this.race);
        console.log("subRace =", this.subRace);
        console.log("baseRace =", baseRace);
        console.log("raceOriginTable =", this.raceOriginTable);
    
        this.origin1 = this.raceOriginTable[baseRace].origin1[d];
        this.history1 = this.origin1;
    },

    rollParameterDice() {
      this.parameterDice = rollParameterDice();
    },

    totalAbility() {
        return Object.values(this.abilities).reduce((sum, val) => sum + val, 0);
      },
      isBeginnerReliefAvailable() {
        return this.totalAbility() <= 15 && !this.reliefApplied;
    },
    applyRaceBonus() {
        // 追加種族の仕様
        const sub = this.subRaceTable[this.subRace];

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

    isOriginAssigned(d) {
      return Object.values(this.assignedOrigin).includes(d);
    },
  }
};
</script>