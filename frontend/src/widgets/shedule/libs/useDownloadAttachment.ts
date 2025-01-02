import {message} from 'antd'
import {useCallback} from 'react'
import {useLazyGetAttachmentData} from 'shared/libs/query'
import {AttachmentData} from 'shared/types'

const downloadBase64 = (base64Data: string, fileName: string) => {
	const link = document.createElement('a')
	link.href = `data:application/octet-stream;base64,${base64Data}`
	link.download = fileName

	document.body.appendChild(link)
	link.click()

	document.body.removeChild(link)
	message.success(`Сохранено как ${fileName}`)
}

const useDownloadAttachment = () => {
	const [getAttachmentData] = useLazyGetAttachmentData()

	return useCallback(async (attachment: AttachmentData) => {
		try {
			const {data, isError} = await getAttachmentData({attachmentId: attachment.attachmentId})
			if (!data?.data || isError) {
				message.error('Что-то пошло не так, повторите попытку позже.')
				return
			}
			const base64Data = data.data
			const fileName = attachment.name
			downloadBase64(base64Data, fileName)
		}
		catch (error) {
			message.error('Что-то пошло не так, повторите попытку позже.')
			console.error('Error downloading attachment:', error)
		}
	}, [getAttachmentData])
}

export {
	useDownloadAttachment,
	downloadBase64,
}
