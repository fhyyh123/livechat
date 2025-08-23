<template>
    <div>
        <div class="feedback" :class="change === true ? 'on' : ''">
            <div class="feedback-header acea-row">
                <span class="sp1">Customer service not online</span>
                <div>
                    <!--<Icon type="md-remove" color="#fff" size="18" class="mr10"/>-->
                    <Icon type="md-close" color="#fff" size="18" @click="close"/>
                </div>
            </div>
            <div v-if="!isShow">
                <div class="feedback-conent mb20">
                    <div class="ft" v-text="notice"></div>
                </div>
                <div>
                    <Form :model="formItem" ref="formItem" class="pl15" :rules="ruleValidate">
                        <FormItem prop="rela_name">
                            <Input v-model="formItem.rela_name" placeholder="Please enter your name"></Input>
                        </FormItem>
                        <FormItem prop="phone">
                            <Input v-model="formItem.phone" placeholder="Please enter your phone number"></Input>
                        </FormItem>
                        <FormItem prop="content">
                            <Input v-model="formItem.content" class="mb10" type="textarea" placeholder="Please enter your message"></Input>
                        </FormItem>
                        <FormItem>
                            <Button type="primary" @click="handleSubmit('formItem')" style="width: 100%">Submit</Button>
                        </FormItem>
                    </Form>
                </div>
            </div>
            <div class="sure" v-if="isShow">
                 <div class="sure-yuan"><Icon type="md-checkmark" color="#fff" size="30"/></div>
                <div class="sp1 mb10">Submission successful</div>
                <div class="sp2 mb30">Your information has been submitted successfully, and we will contact you as soon as possible!</div>
                <Button type="primary" @click="close">OK</Button>
            </div>

        </div>
       <div class="maskModel" @touchmove.prevent v-show="change === true"></div>
    </div>
</template>

<script>
    import { feedbackDataApi, feedbackFromApi } from '@/api/kefu';
    export default {
        name: "feedback",
        props: {
            change: Boolean
        },
        data () {
            return {
                isShow: false,
                formItem: {
                    rela_name: '',
                    content: '',
                    phone: ''
                },
                notice: '',
                ruleValidate: {
                    rela_name: [
                        { required: true, message: 'Please enter your name', trigger: 'blur' }
                    ],
                    content: [
                        { required: true, message: 'Please enter your message', trigger: 'blur' }
                    ],
                    phone: [
                        { required: true, message: 'Please enter your phone number', trigger: 'blur' },
                        {
                            validator: (rule, value, callback) => {
                                if (!value) { // required 已处理
                                    return callback();
                                }
                                // 去掉所有非数字计算长度
                                const digits = value.replace(/\D/g, '');
                                // E.164: 6-15 位数字
                                if (digits.length < 6 || digits.length > 15) {
                                    return callback(new Error('Invalid phone number format'));
                                }
                                // 允许 + 开头及空格 - ( ) . 分隔
                                const compact = value.replace(/[\s\-().]/g, '');
                                if (!/^\+?\d{6,15}$/.test(compact)) {
                                    return callback(new Error('Invalid phone number format'));
                                }
                                return callback();
                            },
                            trigger: 'blur'
                        }
                    ]
                }
            }
        },
        mounted () {
            this.getNotice();
        },
        methods: {
            handleSubmit (name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        feedbackFromApi(this.formItem).then((res) => {
                            this.isShow = true;
                        }).cache(err => {
                            this.$Message.error(err.msg);
                        });
                    } else {

                    }
                })
            },
            close: function () {
                this.$emit('closeChange', false);
            },
            // 广告
            getNotice () {
                feedbackDataApi().then((res) => {
                    this.notice = res.data.feedback;
                }).cache(err => {
                    this.$Message.error(err.msg);
                });
            }
        }
    }
</script>

<style scoped lang="less">
    .maskModel{
        z-index: 7777 !important;
    }
    .on{
        opacity:1 !important;
        transform: scale(1) !important;
        -webkit-transform:scale(1) !important;
        -o-transform:scale(1) !important;
        -moz-transform:scale(1) !important;
        -ms-transform:scale(1) !important;
    }
    .pl15{
        padding: 0 15px;
    }
    .sure{
        width: 100%;
        height: 480px;
        text-align: center;
        &-yuan{
            width: 70px;
            height: 70px;
            border-radius: 50%;
            overflow: hidden;
            background: #55D443;
            margin: 54px auto;
            line-height: 70px;
        }
        .sp1{
            color: #333333;
            font-size: 16px;
        }
        .sp2{
            color: #999999;
            font-size: 13px;
        }
    }
    .feedback {
        position:fixed;
        width: 320px;
        height: 530px;
        border-radius: 2px;
        background-color:#fff;
        z-index:9999;
        top: 50%;
        left: 50%;
        margin-left: -150px;
        margin-top: -237px;
        transition:all 0.3s ease-in-out 0s;
        -webkit-transition:all 0.3s ease-in-out 0s;
        -o-transition:all 0.3s ease-in-out 0s;
        -moz-transition:all 0.3s ease-in-out 0s;
        -webkit-transform:scale(0);
        -o-transform:scale(0);
        -moz-transform:scale(0);
        -ms-transform:scale(0);
        transform: scale(0);
        opacity:0;
        &-header {
            width: 100%;
            height: 50px;
            line-height: 50px;
            padding: 0 15px;
            background: linear-gradient(270deg, #1890FF 0%, #3875EA 100%);
            align-items: center;
            justify-content: space-between;
            .sp1{
                color: #fff;
                font-size: 16px;
            }
        }
        &-conent {
            padding: 15px;
            .ft{
                color: #333333;
                font-size: 13px;
            }
        }
    }
</style>
