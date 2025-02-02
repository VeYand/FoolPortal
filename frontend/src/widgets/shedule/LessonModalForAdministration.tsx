import {Modal, Form, Input, Select, Button, message} from 'antd'
import {useState, useEffect} from 'react'
import {formatDateToISO} from 'shared/libs'
import {
	useLazyCreateLesson,
	useLazyDeleteLesson,
	useLazyUpdateLesson,
} from 'shared/libs/query'
import {
	LessonData,
	LocationData,
	GroupData,
	CourseData,
	TeacherSubjectData,
	SubjectData,
	UserData,
	AttachmentData,
} from 'shared/types'
import {AttachmentUploadBlock} from './AttachmentUploadBlock'
import {formatStartTime} from './libs/formatStartTime'
import {useFetchLessonAttachments} from './libs/useFetchLessonAttachments'
import {useProcessAttachments} from './libs/useProcessAttachments'

type LessonModalProps = {
	open: boolean,
	setOpened: (opened: boolean) => void,
	selectedLesson?: LessonData,
	locations: LocationData[],
	courses: CourseData[],
	teacherSubjects: TeacherSubjectData[],
	subjects: SubjectData[],
	teachers: UserData[],
	groups: GroupData[],
	refetch: () => void,
}

type DetailedAttachmentData = AttachmentData & {
	file?: string,
}

const LessonModalForAdministration = ({
	open,
	setOpened,
	selectedLesson,
	locations,
	courses,
	teacherSubjects,
	subjects,
	teachers,
	groups,
	refetch,
}: LessonModalProps) => {
	const [form] = Form.useForm()
	const [selectedTeacherId, setSelectedTeacherId] = useState<string | undefined>(undefined)
	const [selectedSubjectId, setSelectedSubjectId] = useState<string | undefined>(undefined)
	const [selectedGroupId, setSelectedGroupId] = useState<string | undefined>(undefined)

	const [filteredSubjectIds, setFilteredSubjectIds] = useState<string[]>(subjects.map(s => s.subjectId))
	const [filteredTeacherIds, setFilteredTeacherIds] = useState<string[]>(teachers.map(t => t.userId))
	const [filteredGroupIds, setFilteredGroupIds] = useState<string[]>(groups.map(g => g.groupId))

	const [createLesson] = useLazyCreateLesson()
	const [updateLesson] = useLazyUpdateLesson()
	const [deleteLesson] = useLazyDeleteLesson()

	useEffect(() => {
		setFilteredSubjectIds(subjects.map(s => s.subjectId))
		setFilteredTeacherIds(teachers.map(t => t.userId))
		setFilteredGroupIds(groups.map(g => g.groupId))
	}, [groups, open, subjects, teachers])

	const originalAttachments = useFetchLessonAttachments(selectedLesson?.lessonId)
	const [modifiedAttachments, setModifiedAttachments] = useState<DetailedAttachmentData[]>([])
	useEffect(() => {
		setModifiedAttachments(originalAttachments)
	}, [originalAttachments])
	const processAttachments = useProcessAttachments()
	const isTempAttachment = (attachmentId: string) => {
		const originalAttachmentIds = originalAttachments.map(a => a.attachmentId)
		return !originalAttachmentIds.includes(attachmentId)
	}
	const getTempAttachmentData = (attachmentId: string): string | undefined => modifiedAttachments.find(a => a.attachmentId === attachmentId)?.file

	const handleDeleteLesson = async (lessonId: string) => {
		const data = await deleteLesson({lessonId})
		if (data.isSuccess) {
			message.success('Пара успешно удалена.')
			form.resetFields()
			setOpened(false)
			refetch()
		}
		else {
			message.error('Что-то пошло не так, повторите попытку позже.')
		}
	}

	const handleSaveLesson = async (lesson: Partial<LessonData>) => {
		if (lesson.lessonId) {
			const data = await updateLesson({
				...lesson,
				date: lesson.date ? formatDateToISO(lesson.date) : undefined,
				lessonId: lesson.lessonId,
			})

			if (!data.isSuccess) {
				message.error('Что-то пошло не так, проверьте правильность заполнения формы!')
				return
			}

			await processAttachments(lesson.lessonId, originalAttachments, modifiedAttachments)
		}
		else {
			const data = await createLesson({
				...lesson,
				date: formatDateToISO(lesson.date as Date),
				startTime: lesson.startTime as number,
				duration: lesson.duration as number,
				courseId: lesson.courseId as string,
				locationId: lesson.locationId as string,
				description: lesson.description,
			})

			const createdLessonId = data.data?.lessonId

			if (!data.isSuccess || !createdLessonId) {
				message.error('Что-то пошло не так, проверьте правильность заполнения формы!')
				return
			}

			await processAttachments(createdLessonId, originalAttachments, modifiedAttachments)
		}

		message.success('Пара успешно обновлена.')
		form.resetFields()
		setOpened(false)
		refetch()
	}


	const handleTeacherChange = (teacherId: string) => {
		setSelectedTeacherId(teacherId)

		const availableTeacherSubjects = teacherSubjects.filter(ts => ts.teacherId === teacherId)
		const availableSubjectIds = availableTeacherSubjects.map(ts => ts.subjectId)
		const availableGroupIds = courses
			.filter(c => availableTeacherSubjects.some(ts => ts.teacherSubjectId === c.teacherSubjectId))
			.map(c => c.groupId)

		if (!availableSubjectIds.includes(selectedSubjectId ?? '')) {
			setSelectedSubjectId(undefined)
		}
		if (!availableGroupIds.includes(selectedGroupId ?? '')) {
			setSelectedGroupId(undefined)
		}

		setFilteredSubjectIds(availableSubjectIds)
		setFilteredGroupIds(availableGroupIds)
	}

	const handleSubjectChange = (subjectId: string) => {
		setSelectedSubjectId(subjectId)

		const availableTeacherIds = teacherSubjects
			.filter(ts => ts.subjectId === subjectId)
			.map(ts => ts.teacherId)

		const availableGroupIds = courses
			.filter(c => c.teacherSubjectId === teacherSubjects.find(ts => ts.subjectId === subjectId)?.teacherSubjectId)
			.map(c => c.groupId)

		if (!availableTeacherIds.includes(selectedTeacherId ?? '')) {
			setSelectedTeacherId(undefined)
		}
		if (!availableGroupIds.includes(selectedGroupId ?? '')) {
			setSelectedGroupId(undefined)
		}

		setFilteredTeacherIds(availableTeacherIds)
		setFilteredGroupIds(availableGroupIds)
	}

	const handleGroupChange = (groupId: string) => {
		setSelectedGroupId(groupId)

		const availableTeacherSubjectIds = courses
			.filter(c => c.groupId === groupId)
			.map(c => c.teacherSubjectId)

		const availableSubjectIds = teacherSubjects
			.filter(ts => availableTeacherSubjectIds.includes(ts.teacherSubjectId))
			.map(ts => ts.subjectId)

		const availableTeacherIds = teacherSubjects
			.filter(ts => availableTeacherSubjectIds.includes(ts.teacherSubjectId))
			.map(ts => ts.teacherId)

		if (!availableTeacherIds.includes(selectedTeacherId ?? '')) {
			setSelectedTeacherId(undefined)
		}
		if (!availableSubjectIds.includes(selectedSubjectId ?? '')) {
			setSelectedSubjectId(undefined)
		}

		setFilteredTeacherIds(availableTeacherIds)
		setFilteredSubjectIds(availableSubjectIds)
	}

	useEffect(() => {
		if (selectedLesson) {
			form.setFieldsValue({
				...selectedLesson,
				date: selectedLesson.date.toISOString().split('T')[0],
				startTime: formatStartTime(selectedLesson.startTime),
			})
		}
		else {
			form.resetFields()
		}
	}, [selectedLesson, form])

	const handleSubmit = () => {
		form.validateFields()
			.then(values => {
				const [hours, minutes] = values.startTime.split(':').map(Number)
				const startTimeInMinutes = hours * 60 + minutes

				handleSaveLesson({
					...selectedLesson,
					...values,
					startTime: startTimeInMinutes,
					date: new Date(`${values.date}T00:00:00`),
					duration: Number(values.duration),
					courseId: courses.find(c =>
						teacherSubjects.find(ts => ts.teacherId === selectedTeacherId && ts.subjectId === selectedSubjectId)?.teacherSubjectId === c.teacherSubjectId
						&& selectedGroupId === c.groupId,
					)?.courseId,
				})
			})
			.catch(info => {
				console.error('Validation failed:', info)
			})
	}
	const onCancel = () => {
		form.resetFields()
		setOpened(false)
	}

	return (
		<Modal
			title={selectedLesson ? 'Редактировать пару' : 'Создать пару'}
			open={open}
			onCancel={onCancel}
			footer={[
				...(selectedLesson
					? [
						<Button
							key="delete"
							danger
							onClick={() => handleDeleteLesson(selectedLesson?.lessonId ?? '')}
						>
							{'Удалить'}
						</Button>,
					]
					: []),
				<Button key="cancel" onClick={onCancel}>{'Отмена'}</Button>,
				<Button key="submit" type="primary" onClick={handleSubmit}>{'Сохранить'}</Button>,
			]}
		>
			<Form
				form={form}
				initialValues={selectedLesson ? {...selectedLesson} : {}}
				layout="vertical"
			>
				<Form.Item
					name="date"
					label="Дата"
					rules={[{required: true, message: 'Пожалуйста, выберите дату!'}]}
				>
					<Input type="date" lang={'ru'}/>
				</Form.Item>
				<Form.Item
					name="startTime"
					label="Время начала"
					rules={[{required: true, message: 'Пожалуйста, укажите время начала!'}]}
				>
					<Input type="time" lang={'ru'}/>
				</Form.Item>
				<Form.Item
					name="duration"
					label="Продолжительность (минуты)"
					rules={[{required: true, message: 'Пожалуйста, укажите продолжительность!'}]}
				>
					<Input type="number" min={1}/>
				</Form.Item>
				<Form.Item
					name="teacherId"
					label="Учитель"
					rules={[{required: true, message: 'Пожалуйста, выберите учителя!'}]}
				>
					<Select
						placeholder="Выберите учителя"
						onChange={handleTeacherChange}
						value={selectedTeacherId}
					>
						{teachers.filter(t => filteredTeacherIds.includes(t.userId)).map(teacher => (
							<Select.Option key={teacher.userId} value={teacher.userId}>
								{`${teacher.firstName} ${teacher.lastName}`}
							</Select.Option>
						))}
					</Select>
				</Form.Item>
				<Form.Item
					name="subjectId"
					label="Предмет"
					rules={[{required: true, message: 'Пожалуйста, выберите предмет!'}]}
				>
					<Select
						placeholder="Выберите предмет"
						onChange={handleSubjectChange}
						value={selectedSubjectId}
					>
						{subjects.filter(s => filteredSubjectIds.includes(s.subjectId)).map(subject => (
							<Select.Option key={subject.subjectId} value={subject.subjectId}>
								{subject.name}
							</Select.Option>
						))}
					</Select>
				</Form.Item>
				<Form.Item
					name="groupId"
					label="Группа"
					rules={[{required: true, message: 'Пожалуйста, выберите группу!'}]}
				>
					<Select
						placeholder="Выберите группу"
						onChange={handleGroupChange}
						value={selectedGroupId}
					>
						{groups.filter(g => filteredGroupIds.includes(g.groupId)).map(group => (
							<Select.Option key={group.groupId} value={group.groupId}>
								{group.name}
							</Select.Option>
						))}
					</Select>
				</Form.Item>
				<Form.Item
					name="locationId"
					label="Место проведения"
					rules={[{required: true, message: 'Пожалуйста, выберите место!'}]}
				>
					<Select placeholder="Выберите место">
						{locations.map(location => (
							<Select.Option key={location.locationId} value={location.locationId}>
								{location.name}
							</Select.Option>
						))}
					</Select>
				</Form.Item>
				<Form.Item
					name="description"
					label="Описание"
				>
					<Input.TextArea rows={4} placeholder="Введите описание"/>
				</Form.Item>
				<AttachmentUploadBlock
					attachments={modifiedAttachments}
					setAttachments={setModifiedAttachments}
					isTempAttachment={isTempAttachment}
					getTempAttachmentData={getTempAttachmentData}
				/>
			</Form>
		</Modal>
	)
}

export {
	LessonModalForAdministration,
}

export type {
	DetailedAttachmentData,
}
