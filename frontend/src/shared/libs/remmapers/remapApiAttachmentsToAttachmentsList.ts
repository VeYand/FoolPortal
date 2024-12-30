import {AttachmentInfo as ApiAttachmentInfo} from 'shared/api'
import {AttachmentData} from 'shared/types'

const remapApiAttachmentToAttachmentData = (attachment: ApiAttachmentInfo): AttachmentData => ({
	attachmentId: attachment.attachmentId,
	name: attachment.name,
	extension: attachment.name,
	description: attachment.description,
})

const remapApiAttachmentsToAttachmentsList = (attachments: ApiAttachmentInfo[]): AttachmentData[] => (
	attachments.map(remapApiAttachmentToAttachmentData)
)

export {
	remapApiAttachmentsToAttachmentsList,
}