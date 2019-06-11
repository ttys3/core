<template>
	<div id="app">
      <h2>Dynamically inserted:</h2>
      <div ref="container">
        <button type="button" @click="onClick">Click to insert</button>
      </div>

      <div class="btn-group block-add">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Add new...
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" style="">
          <li v-for="set in sets">
              <a class="add-button" data-block-type="imagesection">
                  <i class="fa fa-plus"></i> Add new Image with description set
              </a>
          </li>
        </ul>
    </div>
    </div>
</template>

<script>
import Vue from 'vue'
import Markdown from '../Markdown/Markdown'

	export default {
    name: 'app',
    components: { Markdown },
    props: ['sets'],
    methods: {
      onClick(event) {
        console.log(event);
        var ComponentClass = Vue.extend(Markdown)
        var instance = new ComponentClass({
            propsData: { type: 'primary' }
        })
        // instance.$slots.default = ['Click me!']
        instance.$mount() // pass nothing
//         console.log(this.$refs)
        this.$refs.container.appendChild(instance.$el)
        var sets = this.sets
        console.log(Object.entries(sets))
        Object.entries(sets).forEach(function(set){

          console.log(set)
        });
      }
    }
  }
</script>

<style>
	#app {
		font-family: 'Avenir', Helvetica, Arial, sans-serif;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		text-align: center;
		color: #2c3e50;
		margin-top: 60px;
	}
</style>