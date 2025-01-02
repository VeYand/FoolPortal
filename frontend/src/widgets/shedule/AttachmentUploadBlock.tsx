import {UploadOutlined, DeleteOutlined, DownloadOutlined} from '@ant-design/icons'
import {Upload, Button, List, Input, Modal, Space, Typography, message} from 'antd'
import type {RcFile} from 'antd/es/upload'
import {useState} from 'react'
import {useAppSelector} from '../../shared/redux'
import {USER_ROLE} from '../../shared/types'
import {DetailedAttachmentData} from './LessonModalForAdministration'

type AttachmentUploadBlockProps = {
	attachments: DetailedAttachmentData[],
	setAttachments: (attachments: DetailedAttachmentData[]) => void,
}

const AttachmentUploadBlock = ({attachments, setAttachments}: AttachmentUploadBlockProps) => {
	const currentUser = useAppSelector(state => state.userEntity.user)

	const [isModalOpen, setIsModalOpen] = useState(false)
	const [currentFile, setCurrentFile] = useState<File | null>(null)
	const [fileName, setFileName] = useState('')
	const [fileDescription, setFileDescription] = useState('')

	const handleUpload = (file: RcFile) => {
		setCurrentFile(file)
		setFileName(file.name)
		setIsModalOpen(true)
		return false
	}

	const fileToBase64 = (file: File): Promise<string> => new Promise((resolve, reject) => {
		const reader = new FileReader()
		reader.onload = () => resolve((reader.result as string).split(',')[1] || '')
		reader.onerror = error => reject(error)
		reader.readAsDataURL(file)
	})

	const handleAddFile = async () => {
		if (!currentFile) {
			return
		}

		if (!fileName.trim()) {
			message.error('File name is required.')
			return
		}
		const base64File = await fileToBase64(currentFile)
		const newAttachment: DetailedAttachmentData = {
			attachmentId: `${Date.now()}-${currentFile.name}`,
			name: fileName,
			extension: currentFile.name.split('.').pop() || '',
			file: base64File,
			description: fileDescription,
		}

		setAttachments([...attachments, newAttachment])
		setIsModalOpen(false)
		setCurrentFile(null)
		setFileName('')
		setFileDescription('')
	}

	const handleRemove = (attachmentId: string) => {
		setAttachments(attachments.filter(item => item.attachmentId !== attachmentId))
	}

	const handleDownloadAttachment = (attachmentId: string) => {
		console.log(`Download attachment with id: ${attachmentId}`)
	}

	return (
		<div>
			{currentUser.role !== USER_ROLE.STUDENT && (
				<Upload beforeUpload={handleUpload} showUploadList={false}>
					<Button icon={<UploadOutlined/>}>{'Добавить вложение'}</Button>
				</Upload>
			)}
			{attachments.length > 0 && (
				<List
					header={<Typography.Title level={5}>{'Вложения'}</Typography.Title>}
					bordered
					dataSource={attachments}
					renderItem={item => (
						<List.Item
							actions={[
								<Button icon={<DownloadOutlined/>}
									onClick={() => handleDownloadAttachment(item.attachmentId)}>{'Скачать'}</Button>,
								...(currentUser.role === USER_ROLE.STUDENT ? [] : [
									<Button icon={<DeleteOutlined/>} danger
										onClick={() => handleRemove(item.attachmentId)}>{'Удалить'}</Button>,
								]),
							]}
						>
							<Space direction="vertical">
								<Typography.Text strong>{item.name}</Typography.Text>
								{item.description && <Typography.Text type="secondary">{item.description}</Typography.Text>}
							</Space>
						</List.Item>
					)}
				/>
			)}

			<Modal
				title="Add File Details"
				open={isModalOpen}
				onOk={handleAddFile}
				onCancel={() => setIsModalOpen(false)}
				okText="Add File"
				cancelText="Cancel"
			>
				<Input
					placeholder="File Name (Required)"
					value={fileName}
					onChange={e => setFileName(e.target.value)}
					style={{marginBottom: 10}}
				/>
				<Input.TextArea
					placeholder="Description (Optional)"
					value={fileDescription}
					onChange={e => setFileDescription(e.target.value)}
				/>
			</Modal>
		</div>
	)
}

export {
	AttachmentUploadBlock,
}