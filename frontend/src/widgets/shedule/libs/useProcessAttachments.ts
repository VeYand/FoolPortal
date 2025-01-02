import {
	useLazyAddAttachmentToLesson,
	useLazyCreateAttachment,
	useLazyDeleteAttachment,
} from 'shared/libs/query'
import {AttachmentData} from 'shared/types'
import {DetailedAttachmentData} from '../LessonModalForAdministration'

const useProcessAttachments = () => {
	const [createAttachment] = useLazyCreateAttachment()
	const [addAttachmentToLesson] = useLazyAddAttachmentToLesson()
	const [deleteAttachment] = useLazyDeleteAttachment()

	return async (lessonId: string, originalAttachments: AttachmentData[], modifiedAttachments: DetailedAttachmentData[]) => {
		const originalAttachmentIds = originalAttachments.map(a => a.attachmentId)
		const modifiedAttachmentIds = modifiedAttachments.map(a => a.attachmentId)

		const attachmentIdsToDelete = originalAttachmentIds.filter(attachmentId => !modifiedAttachmentIds.includes(attachmentId))

		await Promise.all(attachmentIdsToDelete.map(attachmentIdToDelete =>
			deleteAttachment({attachmentId: attachmentIdToDelete}),
		))

		const createdAttachmentIds: string[] = []

		const attachmentIdsToCreate = modifiedAttachmentIds.filter(attachmentId => !originalAttachmentIds.includes(attachmentId))

		const creationPromises = attachmentIdsToCreate.map(async attachmentIdToCreate => {
			const attachment = modifiedAttachments.find(a => a.attachmentId === attachmentIdToCreate)
			if (attachment?.file) {
				const {data: createdAttachmentData} = await createAttachment({
					attachment: {
						...attachment,
						file: attachment.file,
					},
				})
				if (createdAttachmentData?.attachmentId) {
					createdAttachmentIds.push(createdAttachmentData.attachmentId)
				}
			}
		})

		await Promise.all(creationPromises)

		await Promise.all(createdAttachmentIds.map(attachmentId =>
			addAttachmentToLesson({lessonId, attachmentId}),
		))
	}
}

export {
	useProcessAttachments,
}
