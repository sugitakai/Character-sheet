<template>
  <div>
    <h3>身体的特徴</h3>

    <label>体型：</label>
    <select v-model="physicalFeatures.bodyType">
      <option v-for="t in physicalOptions.bodyType" :key="t">{{ t }}</option>
    </select>

    <label>身長：</label>
    <select v-model="physicalFeatures.height">
      <option v-for="h in physicalOptions.height" :key="h">{{ h }}</option>
    </select>

    <label>髪色：</label>
    <select v-model="physicalFeatures.hairColor">
      <option v-for="c in physicalOptions.hairColor" :key="c">{{ c }}</option>
    </select>

    <label>目の色：</label>
    <select v-model="physicalFeatures.eyeColor">
      <option v-for="e in physicalOptions.eyeColor" :key="e">{{ e }}</option>
    </select>

    <label>肌の色：</label>
    <select v-model="physicalFeatures.skinColor">
      <option v-for="s in physicalOptions.skinColor" :key="s">{{ s }}</option>
    </select>

    <label>その他特徴：</label>
    <select v-model="physicalFeatures.special">
      <option v-for="sp in physicalOptions.special" :key="sp">{{ sp }}</option>
    </select>  
  </div>
  <h3>キャラ設定</h3>
  <textarea name="message">何かあれば追記</textarea>
</template>

<script>
export default {
  data() {
    return {
      physicalFeatures: {
        bodyType: "",
        height: "",
        hairColor: "",
        eyeColor: "",
        skinColor: "",
        special: ""
      },
      physicalOptions: {
        bodyType: ["細身", "普通", "筋肉質", "肥満"],
        height: ["低い", "普通", "高い"],
        hairColor: ["黒", "茶", "金", "赤", "白", "灰"],
        eyeColor: ["黒", "茶", "青", "緑", "灰"],
        skinColor: ["白", "褐色", "黒", "赤み"],
        special: ["なし", "傷跡", "刺青", "義肢", "眼帯"]
      }
    };
  }
};
</script>
