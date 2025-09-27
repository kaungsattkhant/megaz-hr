<template>
    <tbody>
        <tr v-for="n in rows" :key="n">
            <td v-for="header in colsCount" :key="header" scope="col" class="">
                <div class="skeleton-cell">&nbsp;</div>
            </td>
        </tr>
    </tbody>
</template>

<script>
export default {
    name: "TableSkeleton",
    props: {
        rows: { type: Number, default: 20 }, // always 20 rows
        cols: { type: Number, required: true }, // must match actual table
    },

    data() {
        return {
            colsCount: null,
        };
    },

    mounted(){
        const table = this.$el.closest("table");
        if (table) {
            const thCount = table.querySelectorAll("thead th").length;
            console.log("Number of columns:", thCount);
            this.colsCount = thCount
        }
    },
};
</script>

<style scoped>
.skeleton-cell {
    flex: 1;
    height: 20px;
    border-radius: 4px;
    background: linear-gradient(90deg, #eee 25%, #ddd 50%, #eee 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    100% {
        background-position: -200% 0;
    }
}
</style>
