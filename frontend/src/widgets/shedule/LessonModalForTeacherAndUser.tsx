import {Button, Modal} from 'antd'
import {useEffect, useMemo, useState} from 'react'
import {getViewableUserName} from 'shared/libs'
import {useAppSelector} from 'shared/redux'
import {GroupData, LessonData, LocationData, SubjectData, USER_ROLE, UserData} from 'shared/types'
import {AttachmentUploadBlock} from './AttachmentUploadBlock'
import {DetailedAttachmentData} from './LessonModalForAdministration'
import {formatStartTime} from './libs/formatStartTime'
import {useFetchLessonAttachments} from './libs/useFetchLessonAttachments'
import {useProcessAttachments} from './libs/useProcessAttachments'

type LessonReadModalProps = {
	open: boolean,
	setOpened: (opened: boolean) => void,
	selectedLesson?: LessonData,
	locations: LocationData[],
	subjects: SubjectData[],
	users: UserData[],
	groups: GroupData[],
}

const LessonModalForTeacherAndUser = ({
	open,
	setOpened,
	selectedLesson,
	locations,
	subjects,
	users,
	groups,
}: LessonReadModalProps) => {
	const currentUser = useAppSelector(state => state.userEntity.user)

	useEffect(() => {
		if (!selectedLesson) {
			setOpened(false)
		}
	}, [selectedLesson, setOpened])

	const onCancel = () => {
		setOpened(false)
	}

	const teacher = useMemo(() => users.find(u => u.userId === selectedLesson?.teacherId), [selectedLesson?.teacherId, users])

	const originalAttachments = useFetchLessonAttachments(selectedLesson?.lessonId)
	const [modifiedAttachments, setModifiedAttachments] = useState<DetailedAttachmentData[]>([])
	useEffect(() => {
		setModifiedAttachments(originalAttachments)
	}, [originalAttachments])
	const processAttachments = useProcessAttachments()

	const handleSubmit = () => {
		if (selectedLesson?.lessonId) {
			processAttachments(selectedLesson.lessonId, originalAttachments, modifiedAttachments)
		}
		setOpened(false)
	}

	return (
		<Modal
			title={subjects.find(s => s.subjectId === selectedLesson?.subjectId)?.name ?? 'Данные пары'}
			open={open}
			onCancel={onCancel}
			footer={[
				<Button key="cancel" onClick={onCancel}>Закрыть</Button>,
				...(currentUser.role === USER_ROLE.TEACHER && selectedLesson
					? [
						<Button key="submit" type="primary" onClick={handleSubmit}>{'Сохранить'}</Button>,
					]
					: []),
			]}
			width={600}
		>
			{selectedLesson ? (
				<div>
					<div style={{marginBottom: '8px'}}>
						<strong>Группа:</strong> {groups.find(g => g.groupId === selectedLesson.groupId)?.name ?? '-'}
					</div>
					<div style={{marginBottom: '8px'}}>
						<strong>Учитель:</strong> {teacher ? getViewableUserName(teacher) : '-'}
					</div>
					<div style={{marginBottom: '8px'}}>
						<strong>Дата и
							время:</strong> {selectedLesson.date ? `${new Date(selectedLesson.date).toLocaleDateString()} ${formatStartTime(selectedLesson.startTime)}` : '-'}
					</div>
					<div style={{marginBottom: '8px'}}>
						<strong>Продолжительность:</strong> {selectedLesson.duration} минут
					</div>
					<div style={{marginBottom: '8px'}}>
						<strong>Место
							проведения:</strong> {locations.find(l => l.locationId === selectedLesson.lessonId)?.name ?? '-'}
					</div>
					<div style={{marginBottom: '8px'}}>
						<strong>Описание:</strong> {selectedLesson.description || '-'}
					</div>
					<AttachmentUploadBlock attachments={modifiedAttachments} setAttachments={setModifiedAttachments}/>
				</div>
			) : (
				<div>Нет данных о выбранной паре.</div>
			)}
		</Modal>
	)
}

export {LessonModalForTeacherAndUser}
