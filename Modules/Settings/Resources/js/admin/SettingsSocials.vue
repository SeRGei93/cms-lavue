<template>
    <div v-if="!loading">
        <p class="title_form">
            {{ $t('Settings.socials_on_off') }}
        </p>
        <form @submit.prevent="onSubmit">
            <div class="form-row" v-for="(item, index) in items">
                <div class="col-sm-6 col-lg-3 form-group">
                    <label for="name">{{ $t('Settings.title') }}</label>
                    <input type="text" v-model="item.name" :class="['form-control', errors[index + '.name'] ? ' is-invalid' : '']"
                           id="name">
                </div>
                <div class="col-sm-6 col-lg-4 form-group">
                    <label for="url">{{ $t('Settings.url') }}</label>
                    <input type="text" v-model="item.url" :class="['form-control', errors[index + '.url'] ? ' is-invalid' : '']"
                           id="url">
                    <div class="invalid-feedback" v-if="errors[index + '.url']">
                        <strong v-for="error in errors[index + '.url']">{{ error }}</strong>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 form-group">
                    <label for="class_icon">{{ $t('Settings.class_icon') }}</label>
                    <input type="text" v-model="item.class_icon"
                           :class="['form-control', errors[index + '.class_icon'] ? ' is-invalid' : '']" id="class_icon">
                </div>
                <div class="col-sm-5 col-lg-1 form-group">
                    <label for="sort">{{ $t('Settings.sort') }}</label>
                    <input type="text" v-model="item.sort" :class="['form-control', errors[index + '.sort'] ? ' is-invalid' : '']"
                           id="sort">
                </div>
                <div class="col d-flex align-items-center justify-content-center" :title="$t('Settings.delete')"
                     @click.prevent="deleteSocial(index)" v-if="canCreate">
                    <button type="button" class="btn btn-danger">
                        <fa :icon="['fas', 'trash-alt']"/>
                    </button>
                </div>
            </div>
            <p class="mt-4 d-flex justify-content-between" v-if="canCreate">
                <button type="button" class="btn btn-primary" :title="$t('Settings.add')" @click.prevent="addSocial()">
                    <fa :icon="['fas', 'plus']"/>
                </button>
                <button :class="{'btn btn-primary': true, 'btn-loading': submit}" type="submit" :title="$t('Settings.save')"
                        :disabled="submit">
                    <i class="fas fa-save"></i>
                    <fa :icon="['fas', 'save']"/>
                </button>
            </p>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    import {mapGetters} from "vuex";

    export default {
        data() {
            return {
                items: [],
                loading: true,
                submit: false,
                errors: {},
            }
        },
        computed: {
            ...mapGetters({
                authenticated: 'auth/check',
                permissions: 'auth/checkPermission'
            }),
            source() {
                const arrayRoute = this.$route.name.split('.');
                return `/admin/${arrayRoute[0]}`;
            },
            canCreate() {
                if (this.authenticated) {
                    const arrayName = this.$router.currentRoute.name.split('.');
                    return this.permissions(arrayName[0], 'create')
                }

                return false;
            }
        },
        mounted() {
            this.getItems()
        },
        methods: {
            getItems() {
                axios.get(this.source).then(response => {
                    this.items = response.data.data;
                    this.loading = false;
                }).catch(error => {
                    console.log(error)
                })
            },
            addSocial() {
                this.items.push({
                    name: '',
                    url: '',
                    class_icon: '',
                    sort: '',
                })
            },
            deleteSocial(index) {
                this.items.splice(index, 1)
            },
            onSubmit() {
                this.submit = true;
                axios.post(this.source, this.items).then(response => {
                    this.items = response.data.data;
                    this.$bvToast.toast(this.$t('Settings.data_save'), {
                        title: this.$t('Settings.status'),
                        variant: 'info',
                        solid: true
                    })
                    this.$nextTick(() => {
                        this.submit = false;
                    })
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors
                        this.submit = false;
                    }
                })
            }
        }
    }
</script>