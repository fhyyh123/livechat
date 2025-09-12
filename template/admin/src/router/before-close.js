
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2021 https://www.crmeb.com All rights reserved.



import { Modal } from 'iview'

const beforeClose = {
  before_close_normal: (resolve) => {
    Modal.confirm({
      title: '确定要关闭这一页吗',
      onOk: () => {
        resolve(true)
      },
      onCancel: () => {
        resolve(false)
      }
    })
  }
};

export default beforeClose
