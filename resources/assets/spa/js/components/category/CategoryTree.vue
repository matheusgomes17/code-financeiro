<template>
    <ul class="category-tree">
        <li v-for="(index, o) in categories" class="category-child">
            <div class="valign-wrapper">
                <a :data-activates="dropdownId(o)" href="#" class="category-symbol"
                   :class="{'green-text': o.children.data.length > 0, 'grey-text': !o.children.data.length}">
                    <i class="material-icons">{{ categoryIcon(o) }}</i>
                </a>
                <ul :id="dropdownId(o)" class="dropdown-content">
                    <li>
                        <a href="#" @click.prevent="categoryNew(o)">Adicionar</a>
                    </li>
                    <li>
                        <a href="#" @click.prevent="categoryEdit(o)">Editar</a>
                    </li>
                    <li>
                        <a href="#">Remover</a>
                    </li>
                </ul>
                <span class="valign">{{{ categoryText(o) }}}</span>
            </div>
            <category-tree :categories="o.children.data" :parent="o"></category-tree>
        </li>
    </ul>
</template>


<script type="text/javascript">
    export default{
        name: 'category-tree', // este name serve para o componente se chamar a si proprio como é mostrado no nosso li <category-tree :categories="o.children.data"></category-tree> para mostra as subcategorias
        props:{
            categories:{
                type: Array,
                required: true
            },
            parent: {
                type: Object,
                required: false,
                'default' (){
                    return null; // caso o parent não seja passado o valor padrão é null
                }
            },
        },
        watch:{
            categories: {
                handler(categories){ // acção quando a propriedade for modificada
                    $('.category-child > div > a').dropdown({
                        hover: true,
                        inDuration: 300,
                        outDuration: 400,
                        belowOrigin: true
                    });
                },
                deep: true, // para assistir os subniveis
            }
        },
        methods:{
            categoryText(category){
                return category.children.data.length > 0 ? `<strong>${category.name}</strong>` : `${category.name}`;

            },
            categoryIcon(category){
                return category.children.data.length > 0 ? 'folder' : 'label';
            },
            dropdownId(category){
                return `category-tree-dropdown-${category.id}`;
            },
            categoryNew(category){
                this.$dispatch('category-new', category);
            },
            categoryEdit(category){
                this.$dispatch('category-edit', category, this.parent);
            }
        }
    }

</script>