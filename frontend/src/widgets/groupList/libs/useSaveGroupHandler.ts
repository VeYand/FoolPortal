type UseSaveGroupHandlerProps = {
	isNewGroup: boolean,

}

const useSaveGroupHandler = ({isNewGroup}: UseSaveGroupHandlerProps) => {
	console.log(isNewGroup)
}

export {
	useSaveGroupHandler,
}