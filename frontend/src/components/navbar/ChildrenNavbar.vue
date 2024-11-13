<template>
    <div class='c-navbar-children'>
        <div class='c-navbar-children_link c-navbar-children_root' @click="toggleNavbar">
            <div class="icon-wrapper">
                <span v-html="childrensProps.svg"></span>
            </div>
            {{ childrensProps.name }}
            <b-icon 
                v-if="toggle"
                class='c-navbar-children_caret'
                icon="caret-down-fill"
                font-scale="0.5" 
                @click="toggleNavbar"
            ></b-icon>
            <b-icon 
                v-else
                class='c-navbar-children_caret'
                icon="caret-right-fill"
                font-scale="0.5"
                @click="toggleNavbar"
            ></b-icon>
        </div>
        <router-link
            v-show="toggle" 

            v-for="(item, key) in childrensProps.children" :key="key"
            class='c-navbar-children_child'
            active-class='c-navbar-children_child--active'
           :to="{ name: item.to }" 
        >
            <span class='c-navbar-children_mark'></span>
            <span class='c-navbar-children_circle'></span>
            <p>
                {{ item.name }}
            </p>
        </router-link>
    </div>
</template>
<script>
    export default {
        name: 'ChildrenNavbar',
        data(){
            return {
                toggle: false,
                active: {}
            }
        },
        props: {
            childrensProps: { type: Object, default: function () {} },
        },
        methods: {
            toggleNavbar(){
                this.toggle = !this.toggle
            },
            setActiveLink(to){
                this.active = to
            }
        },
        mounted(){
            this.childrensProps.children.forEach( (item ) => {
                if(item.to == this.$router.history.current.name){
                    this.toggle = true
                }
            })  
        }
    }
</script>