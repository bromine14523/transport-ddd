<template>
  <v-container>
      <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        :headers="headers"
        :items-length="totalItems"
        :items="serverItems"
        :loading="loading"
        class="elevation-1"
        item-value="name"
        @update:options="loadItems"
      >
        <template v-slot:top>
          <v-toolbar
            flat
          >
            <v-toolbar-title>Vehicles</v-toolbar-title>
            <v-divider
              class="mx-4"
              inset
              vertical
            ></v-divider>
            <v-spacer></v-spacer>
            <v-dialog
              v-model="dialog"
              max-width="500px"
            >
              <template v-slot:activator="{ props }">
                <v-btn
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="props"
                >
                  New Item
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col
                        cols="12"
                        sm="6"
                        md="4"
                      >
                        <v-text-field
                          v-model="editedItem.name"
                          label="Dessert name"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="4"
                      >
                        <v-text-field
                          v-model="editedItem.calories"
                          label="Calories"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="4"
                      >
                        <v-text-field
                          v-model="editedItem.fat"
                          label="Fat (g)"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="4"
                      >
                        <v-text-field
                          v-model="editedItem.carbs"
                          label="Carbs (g)"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="4"
                      >
                        <v-text-field
                          v-model="editedItem.protein"
                          label="Protein (g)"
                        ></v-text-field>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    color="blue-darken-1"
                    variant="text"
                    @click="close"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    color="blue-darken-1"
                    variant="text"
                    @click="save"
                  >
                    Save
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="500px">
              <v-card>
                <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancel</v-btn>
                  <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">OK</v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon
            size="small"
            class="me-2"
            @click="editItem(item.raw)"
          >
            mdi-pencil
          </v-icon>
          <v-icon
            size="small"
            @click="deleteItem(item.raw)"
          >
            mdi-delete
          </v-icon>
        </template>
        <template v-slot:no-data>
          <v-btn
            color="primary"
            @click="initialize"
          >
            Reset
          </v-btn>
        </template>
        <template v-slot:bottom>
          <div class="text-center pt-2">
            <v-pagination
              v-model="page"
              :length="pageCount"
            ></v-pagination>
            <v-text-field
              :model-value="itemsPerPage"
              class="pa-2"
              label="Items per page"
              type="number"
              min="-1"
              max="15"
              hide-details
              @update:model-value="itemsPerPage = parseInt($event, 10)"
            ></v-text-field>
          </div>
        </template>
      </v-data-table-server>
  </v-container>
</template>

<script>

const dessserts = [
  {
    name: 'Frozen Yogurt',
    calories: 159,
    fat: 6.0,
    carbs: 24,
    protein: 4.0,
    iron: '1',
  },
  {
    name: 'Jelly bean',
    calories: 375,
    fat: 0.0,
    carbs: 94,
    protein: 0.0,
    iron: '0',
  },
  {
    name: 'KitKat',
    calories: 518,
    fat: 26.0,
    carbs: 65,
    protein: 7,
    iron: '6',
  },
  {
    name: 'Eclair',
    calories: 262,
    fat: 16.0,
    carbs: 23,
    protein: 6.0,
    iron: '7',
  },
  {
    name: 'Gingerbread',
    calories: 356,
    fat: 16.0,
    carbs: 49,
    protein: 3.9,
    iron: '16',
  },
  {
    name: 'Ice cream sandwich',
    calories: 237,
    fat: 9.0,
    carbs: 37,
    protein: 4.3,
    iron: '1',
  },
  {
    name: 'Lollipop',
    calories: 392,
    fat: 0.2,
    carbs: 98,
    protein: 0,
    iron: '2',
  },
  {
    name: 'Cupcake',
    calories: 305,
    fat: 3.7,
    carbs: 67,
    protein: 4.3,
    iron: '8',
  },
  {
    name: 'Honeycomb',
    calories: 408,
    fat: 3.2,
    carbs: 87,
    protein: 6.5,
    iron: '45',
  },
  {
    name: 'Donut',
    calories: 452,
    fat: 25.0,
    carbs: 51,
    protein: 4.9,
    iron: '22',
  },
]

const VehicleAPI = {
  async fetch ({ page, itemsPerPage, sortBy }) {
    return new Promise(resolve => {
      setTimeout(() => {
        const start = (page - 1) * itemsPerPage
        const end = start + itemsPerPage
        const items = dessserts.slice()

        if (sortBy.length) {
          const sortKey = sortBy[0].key
          const sortOrder = sortBy[0].order
          items.sort((a, b) => {
            const aValue = a[sortKey]
            const bValue = b[sortKey]
            return sortOrder === 'desc' ? bValue - aValue : aValue - bValue
          })
        }

        const paginated = items.slice(start, end)

        resolve({ items: paginated, total: items.length })
      }, 500)
    })
  },
}

export default {
  data: () => ({
    page: 1,
    itemsPerPage: 5,
    dialog: false,
    dialogDelete: false,
    headers: [
      {
        title: 'Dessert (100g serving)',
        align: 'start',
        sortable: false,
        key: 'name',
      },
      { title: 'Calories', key: 'calories' },
      { title: 'Fat (g)', key: 'fat' },
      { title: 'Carbs (g)', key: 'carbs' },
      { title: 'Protein (g)', key: 'protein' },
      { title: 'Actions', key: 'actions', sortable: false },
    ],
    serverItems: [],
    editedIndex: -1,
    editedItem: {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
    defaultItem: {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
    loading: true,
    totalItems: 0
  }),

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },
    pageCount() {
      return Math.ceil(this.serverItems.length / this.itemsPerPage)
    },
  },

  watch: {
    dialog (val) {
      val || this.close()
    },
    dialogDelete (val) {
      val || this.closeDelete()
    },
  },

  created () {
    this.initialize()
  },

  methods: {
    initialize () {
      this.serverItems = [];
    },

    editItem (item) {
      this.editedIndex = this.serverItems.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem (item) {
      this.editedIndex = this.serverItems.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    deleteItemConfirm () {
      this.serverItems.splice(this.editedIndex, 1)
      this.closeDelete()
    },

    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    closeDelete () {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    save () {
      if (this.editedIndex > -1) {
        Object.assign(this.serverItems[this.editedIndex], this.editedItem)
      } else {
        this.serverItems.push(this.editedItem)
      }
      this.close()
    },

    loadItems ({ page, itemsPerPage, sortBy }) {
      this.loading = true
      VehicleAPI.fetch({ page, itemsPerPage, sortBy }).then(({ items, total }) => {
        this.serverItems = items
        this.totalItems = total
        this.loading = false
      })
    },
  },
}
</script>
