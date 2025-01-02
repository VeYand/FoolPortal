import {useCallback, useEffect, useState} from 'react'
import {useLazyListLessonAttachments} from 'shared/libs/query'
import {AttachmentData} from 'shared/types'

const useFetchLessonAttachments = (lessonId?: string) => {
	const [listAttachments] = useLazyListLessonAttachments()
	const [attachments, setAttachments] = useState<AttachmentData[]>([])

	const fetch = useCallback(async () => {
		if (!lessonId) {
			setAttachments([])
			return
		}

		const {data} = await listAttachments({lessonId})

		if (data?.attachments) {
			setAttachments(data.attachments)
		}
	}, [listAttachments, lessonId])

	useEffect(() => {
		fetch()
	}, [fetch])

	return attachments
}

export {
	useFetchLessonAttachments,
}