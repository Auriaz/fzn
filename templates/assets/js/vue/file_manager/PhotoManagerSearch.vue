<template>
    <div  class="form-group form-other">
        <input 
            type="text"
            id="query"
            name="query" 
            class="form-control" 
            v-model="query"
            @input="search"
            required
        />
        <label for="query"><i class="fas fa-search"></i>  Szukaj</label>
    </div>
</template>

<script>
export default {

data() {
    return {
        query: '',
    }
},
props: ['items'],
methods: {
    search() {
        const query = this.query;

        this.$emit('search', query)
    }
},
computed: {
    filter() {
        return this.items.filter((item)=>{
            return item.orginalFilename.match(this.query);
        });
    }
},
}
</script>

