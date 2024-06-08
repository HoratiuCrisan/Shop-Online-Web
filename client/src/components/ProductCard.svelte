<script lang="ts">
    import type Product from '../interfaces/products'
    import {Link} from 'svelte-routing'

    export let product: Product;
</script>

<div class="block w-full bg-white rounded-lg shadow-xl p-2 my-2 mx-6">
        <img 
            src={product.photoUrl} 
            alt="product"
            class="w-full p-2"
        >

        <div class="w-full font-semibold p-2">
            <h1>{product.name}</h1>

            <p class="my-4 font-sans text-sm">{product.description}</p>

            <div class="flex justify-between my-4 ">
                <div class="block">
                    {#if product.discount > 0}
                        <span class="block">
                            <span class="line-through">{product.price}</span> 
                            <span class="mx-2 bg-red-500 rounded-md px-2 py-1 text-white">
                                {product.discount}%
                            </span>
                        </span>
                        <span class="text-red-500">{product.price - product.price / 100 * product.discount}$</span>
                    {:else}
                        <span class="">{product.price}$</span>
                    {/if}
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white rounded-md text-lg px-3 py-1">+</button>
            </div>
        </div>
</div>