import {
	UploadOutlined,
	DeleteOutlined,
	DownloadOutlined,
	FilePdfOutlined,
	FileWordOutlined,
	FileExcelOutlined, FileImageOutlined, FileUnknownOutlined,
} from '@ant-design/icons'
import {Upload, Button, List, Input, Modal, Space, Typography, message} from 'antd'
import type {RcFile} from 'antd/es/upload'
import {useState} from 'react'
import {useAppSelector} from 'shared/redux'
import {AttachmentData, USER_ROLE} from 'shared/types'
import {DetailedAttachmentData} from './LessonModalForAdministration'
import {downloadBase64, useDownloadAttachment} from './libs/useDownloadAttachment'

type AttachmentUploadBlockProps = {
	attachments: DetailedAttachmentData[],
	setAttachments: (attachments: DetailedAttachmentData[]) => void,
	isTempAttachment: (attachmentId: string) => boolean,
	getTempAttachmentData: (attachmentId: string) => string | undefined,
}

const AttachmentUploadBlock = ({attachments, setAttachments, isTempAttachment, getTempAttachmentData}: AttachmentUploadBlockProps) => {
	const currentUser = useAppSelector(state => state.userEntity.user)

	const [isModalOpen, setIsModalOpen] = useState(false)
	const [currentFile, setCurrentFile] = useState<File | null>(null)
	const [fileName, setFileName] = useState('')
	const [fileDescription, setFileDescription] = useState('')
	const downloadAttachment = useDownloadAttachment()

	const handleDownloadAttachment = (attachment: AttachmentData) => {
		if (isTempAttachment(attachment.attachmentId)) {
			downloadBase64(getTempAttachmentData(attachment.attachmentId) ?? '', attachment.name)
			return
		}
		downloadAttachment(attachment)
	}

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

	const getFileIcon = (extension: string) => {
		switch (extension.toLowerCase()) {
			case 'pdf':
				return <FilePdfOutlined />
			case 'doc':
			case 'docx':
				return <FileWordOutlined />
			case 'xls':
			case 'xlsx':
				return <FileExcelOutlined />
			case 'jpg':
			case 'jpeg':
			case 'png':
				return <FileImageOutlined />
			default:
				return <FileUnknownOutlined />
		}
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
								<Button icon={<DownloadOutlined />} onClick={() => handleDownloadAttachment(item)}>{'Скачать'}</Button>,
								...(currentUser.role === USER_ROLE.STUDENT ? [] : [
									<Button icon={<DeleteOutlined />} danger onClick={() => handleRemove(item.attachmentId)}>{'Удалить'}</Button>,
								]),
							]}
						>
							<Space direction="vertical" style={{width: '100%'}}>
								<div style={{display: 'flex', alignItems: 'center'}}>
									<span style={{marginRight: 8, fontSize: 24}}>{getFileIcon(item.extension)}</span>
									<Typography.Text strong style={{fontSize: 16}}>{item.name}</Typography.Text>
								</div>
								{item.description && (
									<Typography.Text type="secondary" style={{fontSize: 14}}>{item.description}</Typography.Text>
								)}
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